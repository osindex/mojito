<?php
use Illuminate\Support\Str;
if (!function_exists('recodeFile')) {
    function recodeFile($path, $avoid = [], $replace = [], $to = null, $append = null, $avoidFunc = null)
    {
        $content = '';
        if (file_exists($path)) {
            $fp = new SplFileObject($path, 'r+');
            if ($fp) {
                while (!$fp->eof()) {
                    $fp->next();
                    $line = $fp->current();
                    if (!Str::contains($line, $avoid)) {
                        $content .= $line;
                    } elseif ($avoidFunc) {
                        $content .= $avoidFunc($line);
                    }
                }
                if (count($replace)) {
                    $content = Str::replaceArray($replace[0], $replace[1], $content);
                }
                if ($to) {
                    $fp = new SplFileObject($to, 'w+');
                    $fp->fwrite(rtrim($content) . $append);
                }
            }
        }
        return $content;
    }
}

if (!function_exists('request_intersect')) {
    /**
     * request intersect
     *
     * @param $keys
     * @return array|ø
     */
    function requestIntersect($keys)
    {
        return array_filter(request()->only(is_array($keys) ? $keys : func_get_args()));
    }
}

if (!function_exists('make_tree')) {
    /**
     * @param array $list
     * @param int $parentId
     * @return array
     */
    function makeTree(array $list, $parentId = 0)
    {
        $tree = [];
        if (empty($list)) {
            return $tree;
        }

        $newList = [];
        foreach ($list as $k => $v) {
            $newList[$v['id']] = $v;
        }

        foreach ($newList as $value) {
            if ($parentId == $value['parent_id']) {
                $tree[] = &$newList[$value['id']];
            } elseif (isset($newList[$value['parent_id']])) {
                $newList[$value['parent_id']]['children'][] = &$newList[$value['id']];
            }
        }

        return $tree;
    }
}
