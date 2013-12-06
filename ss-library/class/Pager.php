<?php
/**
 * Name     : Pager
 * Revision : 2013-04-04
 */

class Pager {
    var $start, $stop, $limit = 9, $rows = 10, $totalRecord = 0, $totalPage = 0, $current = 1, $add = '', $getStartKey = 's', $getStopKey = 'stop', $tools = array('first' => 'First', 'prev' => '...', 'next' => '...', 'last' => 'Last', 'auto' => true), $pages = array(), $uri = null;

    function pager($count, $rows = null, $getStartKey = null, $getStopKey = null) {
        $this->rows        = $rows ? $rows : $this->rows; // kac satir
        $this->getStartKey = !is_null($getStartKey) ? $getStartKey : $this->getStartKey;
        $this->getStopKey  = !is_null($getStopKey) ? $getStopKey : $this->getStopKey;

        $start = ($start = (int) @$_GET[$this->getStartKey]) > 0 ? $start : 0;
        $stop  = ($stop  = (int) @$_GET[$this->getStopKey])  > 0 ? $stop  : 0;

        $this->stop  = (int) ((empty($stop)) ? $this->rows : $stop);
        $this->start = (int) ((empty($start) or $start == 1) ? 0 : ($start * $this->stop) - $this->stop);

        $this->totalRecord = $count;
        $this->totalPage   = @ceil($this->totalRecord / $this->stop);
    }

    function generate($limit = null, $ignore = null, $format = true) {
        if ($this->tools['auto']) {
            $this->tools['first'] = 1;
            $this->tools['last']  = $this->totalPage;
        }

        $this->limit = $limit ? $limit : $this->limit; // kac sayfa

        switch(1) {
            case ($this->totalPage == 1): $this->limit = 1; break;
            case ($this->totalPage == 2): $this->limit = 2; break;
            case ($this->totalPage == 3): $this->limit = 3; break;
            case ($this->totalPage == 4): $this->limit = 4; break;
            case ($this->totalPage == 5): $this->limit = 5; break;
            case ($this->totalPage > 5 and $this->totalPage < 9): $this->limit = 5; break;
            default: $this->limit = $this->limit;
        }

        if ($this->limit > $this->totalPage) return; // @debug

        // bunu if (rewrite) diye kontrol edersin
        $qry = '';
        if (!empty($_GET)) {
            $is_ignoreArray  = $ignore && is_array($ignore);
            $is_ignoreString = $ignore && is_string($ignore);
            // foo,bar,baz
            if (!$is_ignoreArray && strpos($ignore, ',')) {
                $ignore = preg_split('~,~', $ignore, -1, PREG_SPLIT_NO_EMPTY);
                $is_ignoreArray = true;
            }

            foreach ($_GET as $k => $v) {
                if ($k == $this->getStartKey) continue;
                if ($is_ignoreString && $k == $ignore) continue;
                if ($is_ignoreArray && in_array($k, $ignore)) continue;
                $qry .= $k .'='. $v .'&';
            }
        }

        // Built uri
        if ($this->uri == null) {
            $request_uri = $_SERVER['REQUEST_URI'];
            $request_uri = htmlentities(substr($request_uri, 0, strcspn($request_uri, "\n\r")), ENT_QUOTES);
            $request_uri = str_replace(array("\0", '%00'), '', $request_uri);
            $prot = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http';
            $port = $_SERVER['SERVER_PORT'];
            $port = ($port != '' &&
                      (($prot == 'http' && $port != '80') ||
                        ($prot == 'https' && $port != '443'))) ? ':'. $port : '';
            $url = $prot . '://' . $_SERVER['HTTP_HOST'] . $port . $request_uri;
            $url = parse_url($url);
            $this->uri = $url['scheme'] .'://'. $url['host'] . $url['path'] . '?' . $qry;
        }

        $start = ($s = (int) @$_GET[$this->getStartKey]) >= 1 ? $s : 1;
        $stop = $start + $this->limit;

        if (($start - 1) >= 1) {
                $this->pages['first'] = '<a class="first" href="'. $this->uri . $this->getStartKey .'='. '1">'. $this->tools['first'] .'</a>';
                $this->pages['prev'] = '<a class="prev" href="'. $this->uri . $this->getStartKey .'='. ($start - 1) .'">'. $this->tools['prev'] .'</a>';
        }

        $sub = 1;
        $middle = ceil($this->limit / 2);
        $middle_sub = $middle - $sub;
        if ($start >= $middle) {
            $i = $start - $middle_sub;
            $loop = $stop - $middle_sub;
        }
        else {
            $i = $sub;
            $loop = $start == $middle_sub ? $stop - $sub : $stop;
            if ($loop >= $this->limit) {
                $diff = $loop - $this->limit;
                $loop = $loop - $diff + $sub;
            }
        }

        $this->pages['pages'] = '';
        for($i; $i < $loop; $i++) {
            if ($loop <= $this->totalPage) {
                if ($i == $start) {
                    $this->pages['pages'] .= ' <span class="current">'. $i .'</span> ';
                    $this->current = $i;
                } else {
                    $this->pages['pages'] .= ' <a href="'. $this->uri . $this->getStartKey .'='. $i .'">'. $i . '</a> ';
                }
            }
            else {
                $extra = $this->totalPage - $start;
                $j = $start;
                if ($extra < $this->limit)
                    $j = $j - (($this->limit - 1) - $extra);

                for($j; $j <= $this->totalPage; $j++) {
                    if ($j == $start) {
                        $this->pages['pages'] .= ' <span class="current">'. $j .'</span> ';
                        $this->current = $j;
                    } else {
                        $this->pages['pages'] .= ' <a href="'. $this->uri . $this->getStartKey .'='. $j .'">'. $j .'</a> ';
                    }
                }
                break;
            }
        }

        if ($start != $this->totalPage) {
            $this->pages['next'] = '<a class="next" href="'. $this->uri . $this->getStartKey .'='. ($start + 1) .'">'. $this->tools['next'] .'</a>';
            $this->pages['last'] = '<a class="last" href="'. $this->uri . $this->getStartKey .'='. $this->totalPage .'">'. $this->tools['last'] .'</a>';
        }

        if ($format) $this->pages = implode(' ', $this->pages);

        return $this->pages;
    }
}