<?php
namespace App\Core;

class Pagination
{
    public static function resolve(int $total, int $perPage = 25): array
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $pages = max(1, (int)ceil($total / $perPage));
        if ($page > $pages) { $page = $pages; }
        $offset = ($page - 1) * $perPage;
        return ['page' => $page, 'pages' => $pages, 'perPage' => $perPage, 'offset' => $offset];
    }

    public static function render(int $current, int $pages): string
    {
        if ($pages <= 1) { return ''; }
        $qs = $_GET;
        $html = '<nav aria-label="Pagination"><ul class="pagination">';
        $qs['page'] = max(1, $current - 1);
        $html .= '<li class="page-item' . ($current <= 1 ? ' disabled' : '') . '"><a class="page-link" href="?' . http_build_query($qs) . '">Prev</a></li>';
        // Show first, current-1, current, current+1, last
        $display = array_unique([1, max(1, $current - 1), $current, min($pages, $current + 1), $pages]);
        sort($display);
        $prev = 0;
        foreach ($display as $p) {
            if ($prev && $p > $prev + 1) { $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>'; }
            $qs['page'] = $p;
            $active = $p === $current ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="?' . http_build_query($qs) . '">' . $p . '</a></li>';
            $prev = $p;
        }
        $qs['page'] = min($pages, $current + 1);
        $html .= '<li class="page-item' . ($current >= $pages ? ' disabled' : '') . '"><a class="page-link" href="?' . http_build_query($qs) . '">Next</a></li>';
        $html .= '</ul></nav>';
        return $html;
    }
}