<?php
if (!function_exists('inputFile')) {
    function inputFile($path, $content, $type = 'w+')
    {
        $fp = new SplFileObject($path, $type);
        if (!is_string($content)) {
            $content = json_decode($content);
        }
        $fp->fwrite($content);
        $fp = null;
    }
}
if (!function_exists('recodeFile')) {
    /**
     *
     * @param string $path 扫描的文件路径
     * @param array $avoid  指定搜索某些值
     * @param array $replace 数组，替换下标为0的的字符为下标为1的内容()
     * @param null $to  要写入的文件路径
     * @param null $append  追加的内容
     * @param Closure $avoidFunc 在某行中搜索到avoid的值后执行的方法，返回数据将替换avoid内容所在行 与avoid参数对应
     * @return string 返回文本处理后的结果
     */
    function recodeFile($path, $avoid = [], $replace = [], $to = null, $append = null, Closure $avoidFunc = null)
    {
        // 声明结果
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
                if (!is_null($to)) {
                    inputFile($to, rtrim($content) . $append);
                }
            }
            $fp = null;
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
    function request_intersect($keys)
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
    function make_tree(array $list, $parentId = 0)
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
