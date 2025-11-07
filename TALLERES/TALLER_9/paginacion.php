<?php
require_once "config_pdo.php";

class Paginator {
    protected $pdo;
    protected $table;
    protected $perPage;
    protected $currentPage;
    protected $totalRecords;
    protected $conditions = [];
    protected $params = [];
    protected $orderBy = '';
    protected $joins = [];
    protected $fields = ['*'];

    public function __construct(PDO $pdo, $table, $perPage = 10) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->perPage = $perPage;
        $this->currentPage = 1;
    }

    public function select($fields) {
        $this->fields = is_array($fields) ? $fields : func_get_args();
        return $this;
    }

    public function where($condition, $params = []) {
        $this->conditions[] = $condition;
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function join($join) {
        $this->joins[] = $join;
        return $this;
    }

    public function orderBy($orderBy) {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setPage($page) {
        $this->currentPage = max(1, (int)$page);
        return $this;
    }

    public function getTotalRecords() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if (!empty($this->joins)) $sql .= " " . implode(" ", $this->joins);
        if (!empty($this->conditions)) $sql .= " WHERE " . implode(" AND ", $this->conditions);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchColumn();
    }

    public function getResults() {
        $offset = ($this->currentPage - 1) * $this->perPage;
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM {$this->table}";
        if (!empty($this->joins)) $sql .= " " . implode(" ", $this->joins);
        if (!empty($this->conditions)) $sql .= " WHERE " . implode(" AND ", $this->conditions);
        if ($this->orderBy) $sql .= " ORDER BY {$this->orderBy}";
        $sql .= " LIMIT {$this->perPage} OFFSET {$offset}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageInfo() {
        $totalRecords = $this->getTotalRecords();
        $totalPages = ceil($totalRecords / $this->perPage);
        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'has_previous' => $this->currentPage > 1,
            'has_next' => $this->currentPage < $totalPages,
            'previous_page' => $this->currentPage - 1,
            'next_page' => $this->currentPage + 1,
            'first_page' => 1,
            'last_page' => $totalPages,
        ];
    }
}

class CursorPaginator extends Paginator {
    private $cursorField;
    private $cursorValue;
    private $direction;

    public function __construct(PDO $pdo, $table, $cursorField, $perPage = 10) {
        parent::__construct($pdo, $table, $perPage);
        $this->cursorField = $cursorField;
    }

    public function setCursor($value, $direction = 'next') {
        $this->cursorValue = $value;
        $this->direction = $direction;
        return $this;
    }

    public function getResults() {
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM {$this->table}";
        if (!empty($this->joins)) $sql .= " " . implode(" ", $this->joins);
        $conditions = $this->conditions;
        if ($this->cursorValue !== null) {
            $operator = $this->direction === 'next' ? '>' : '<';
            $conditions[] = "{$this->cursorField} {$operator} :cursor";
            $this->params[':cursor'] = $this->cursorValue;
        }
        if (!empty($conditions)) $sql .= " WHERE " . implode(" AND ", $conditions);
        if ($this->orderBy) $sql .= " ORDER BY {$this->orderBy}";
        else {
            $direction = $this->direction === 'next' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY {$this->cursorField} {$direction}";
        }
        $sql .= " LIMIT " . ($this->perPage + 1);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $hasMore = count($results) > $this->perPage;
        if ($hasMore) array_pop($results);
        return [
            'results' => $results,
            'has_more' => $hasMore,
            'next_cursor' => $hasMore ? end($results)[$this->cursorField] : null
        ];
    }
}

define('CACHE_DIR', __DIR__ . '/cache_pages');
if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

function cache_get(string $key) {
    $file = CACHE_DIR . '/' . preg_replace('/[^a-z0-9_.-]/i','_', $key) . '.cache';
    if (!file_exists($file)) return false;
    $meta = json_decode(file_get_contents($file . '.meta') ?: '', true);
    if (!$meta) return false;
    if (time() > ($meta['expires_at'] ?? 0)) {
        @unlink($file); @unlink($file . '.meta');
        return false;
    }
    return unserialize(file_get_contents($file));
}

function cache_set(string $key, $value, int $ttl = 300) {
    $file = CACHE_DIR . '/' . preg_replace('/[^a-z0-9_.-]/i','_', $key) . '.cache';
    file_put_contents($file, serialize($value), LOCK_EX);
    file_put_contents($file . '.meta', json_encode(['expires_at' => time() + $ttl]), LOCK_EX);
}

function infiniteScrollEndpoint(PDO $pdo) {
    $cursor = isset($_GET['cursor']) && $_GET['cursor'] !== '' ? $_GET['cursor'] : null;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $perPage = max(1, min(100, $perPage));
    $filters = [];
    if (!empty($_GET['categoria'])) $filters['categoria_id'] = (int)$_GET['categoria'];
    $cacheKey = 'infinite_' . http_build_query(['cursor'=>$cursor,'per'=>$perPage,'filters'=>$filters]);
    $cached = cache_get($cacheKey);
    if ($cached !== false) {
        header('Content-Type: application/json');
        echo json_encode($cached);
        exit;
    }
    $cp = new CursorPaginator($pdo, 'productos', 'id', $perPage);
    $cp->select('id','nombre','precio','stock')->where('precio >= ?', [0]);
    if (isset($filters['categoria_id'])) $cp->where('categoria_id = ?', [$filters['categoria_id']]);
    $cp->setCursor($cursor, 'next');
    $data = $cp->getResults();
    $payload = ['results' => $data['results'],'has_more' => $data['has_more'],'next_cursor' => $data['next_cursor']];
    cache_set($cacheKey, $payload, 120);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function paginatedCatalog(PDO $pdo) {
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $perPage = max(1, min(200, $perPage));
    $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
    $p = new Paginator($pdo, 'productos', $perPage);
    $p->select('productos.id','productos.nombre','productos.precio','categorias.nombre AS categoria')
      ->join('LEFT JOIN categorias ON productos.categoria_id = categorias.id')
      ->where('productos.precio >= ?', [0])
      ->orderBy('productos.id DESC')
      ->setPage($page);
    $results = $p->getResults();
    $info = $p->getPageInfo();
    return ['results'=>$results,'page_info'=>$info];
}

function exportPaginatedToCSV(PDO $pdo) {
    $type = $_GET['type'] ?? 'page';
    $filename = 'export_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'w');
    if ($type === 'infinite') {
        $cursor = $_GET['cursor'] ?? null;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 100;
        $cp = new CursorPaginator($pdo, 'productos', 'id', $perPage);
        $cp->select('id','nombre','precio','stock');
        $cp->setCursor($cursor, 'next');
        $page = $cp->getResults();
        $rows = $page['results'];
    } else {
        $pageNum = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 100;
        $p = new Paginator($pdo, 'productos', $perPage);
        $p->select('productos.id','productos.nombre','productos.precio','productos.stock','categorias.nombre AS categoria')
          ->join('LEFT JOIN categorias ON productos.categoria_id = categorias.id')
          ->setPage($pageNum);
        $rows = $p->getResults();
    }
    if (empty($rows)) {
        fputcsv($out, ['No data']);
        fclose($out);
        exit;
    }
    $headers = array_keys($rows[0]);
    fputcsv($out, $headers);
    foreach ($rows as $r) {
        $line = array_map(fn($v) => is_null($v) ? '' : (string)$v, $r);
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

function registerPageVisit(PDO $pdo, string $pageKey) {
    $file = CACHE_DIR . '/visits_' . preg_replace('/[^a-z0-9_.-]/i','_', $pageKey) . '.cnt';
    $count = file_exists($file) ? (int)file_get_contents($file) : 0;
    $count++;
    file_put_contents($file, (string)$count, LOCK_EX);
    return $count;
}

function getTopCachedPages(int $limit = 10) {
    $files = glob(CACHE_DIR . '/visits_*.cnt');
    $arr = [];
    foreach ($files as $f) $arr[basename($f)] = (int)file_get_contents($f);
    arsort($arr);
    return array_slice($arr, 0, $limit, true);
}

$action = $_GET['action'] ?? 'catalog';
if ($action === 'infinite') {
    infiniteScrollEndpoint($pdo);
} elseif ($action === 'export') {
    exportPaginatedToCSV($pdo);
} else {
    $data = paginatedCatalog($pdo);
    registerPageVisit($pdo, 'catalog_page_' . ($_GET['page'] ?? 1) . '_per_' . ($_GET['per_page'] ?? 10));
    $results = $data['results'];
    $pageInfo = $data['page_info'];
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>Catálogo</title></head><body>
    <form method="get">
        <label>Por página:
            <select name="per_page" onchange="this.form.submit()">
                <option value="5" <?= ($_GET['per_page']??10)==5 ? 'selected':'' ?>>5</option>
                <option value="10" <?= ($_GET['per_page']??10)==10 ? 'selected':'' ?>>10</option>
                <option value="25" <?= ($_GET['per_page']??10)==25 ? 'selected':'' ?>>25</option>
                <option value="50" <?= ($_GET['per_page']??10)==50 ? 'selected':'' ?>>50</option>
            </select>
        </label>
        <input type="hidden" name="action" value="catalog">
    </form>
    <table border="1" cellpadding="6">
        <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Categoría</th></tr></thead>
        <tbody>
        <?php foreach ($results as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id']) ?></td>
                <td><?= htmlspecialchars($r['nombre']) ?></td>
                <td>$<?= number_format($r['precio'],2) ?></td>
                <td><?= htmlspecialchars($r['categoria'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <?php if ($pageInfo['has_previous']): ?>
            <a href="?page=1&per_page=<?= $pageInfo['per_page'] ?>">Primera</a>
            <a href="?page=<?= $pageInfo['previous_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Anterior</a>
        <?php endif; ?>
        <strong>Página <?= $pageInfo['current_page'] ?> / <?= $pageInfo['total_pages'] ?></strong>
        <?php if ($pageInfo['has_next']): ?>
            <a href="?page=<?= $pageInfo['next_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Siguiente</a>
            <a href="?page=<?= $pageInfo['last_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Última</a>
        <?php endif; ?>
    </div>
    <div>
        <a href="?action=export&page=<?= $pageInfo['current_page'] ?>&per_page=<?= $pageInfo['per_page'] ?>">Exportar página a CSV</a>
    </div>
    <div>
        <h4>Páginas más visitadas</h4>
        <ul>
            <?php foreach (getTopCachedPages(5) as $k => $v): ?>
                <li><?= htmlspecialchars($k) ?> — <?= $v ?> visitas</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script>
    let nextCursor = null;
    let loading = false;
    function loadMore() {
        if (loading) return;
        loading = true;
        const per = <?= (int)($_GET['per_page'] ?? 10) ?>;
        const url = '?action=infinite&per_page=' + per + (nextCursor ? '&cursor=' + encodeURIComponent(nextCursor) : '');
        fetch(url).then(r => r.json()).then(data => {
            const tbody = document.querySelector('table tbody');
            data.results.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${row.id}</td><td>${row.nombre}</td><td>$${Number(row.precio).toFixed(2)}</td><td></td>`;
                tbody.appendChild(tr);
            });
            nextCursor = data.next_cursor;
            loading = false;
            if (!data.has_more) window.removeEventListener('scroll', handleScroll);
        });
    }
    function handleScroll() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) loadMore();
    }
    window.addEventListener('scroll', handleScroll);
    </script>
    </body></html>
    <?php
}
$pdo = null;
?>
