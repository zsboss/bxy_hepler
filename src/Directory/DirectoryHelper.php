<?php
/**
 * Created by PhpStorm.
 * User: lengbin
 * Date: 2017/6/5
 * Time: 下午1:17
 */

namespace  Bxy\Helper\Directory;

class DirectoryHelper
{

    /**
     * 检查路径是否存在,不存在则递归生成路径
     *
     * @param string $path 路径
     *
     * @return bool
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function pathExists($path)
    {
        $pathinfo = pathinfo($path . '/tmp.txt');
        if (!empty($pathinfo['dirname'])) {
            if (file_exists($pathinfo['dirname']) === false) {
                if (@mkdir($pathinfo['dirname'], 0777, true) === false) {
                    return false;
                }
            }
        }
        return $path;
    }

    public static $rootDir;

    /**
     * 递归清空目录
     *
     * @param string  $dir      文件夹路径
     * @param boolean $isDelete 是否删除根目录目录
     *
     * @return bool
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function emptyDir($dir, array $filterDir = [], array $filterFile = [], $isDelete = false)
    {
        if (empty(self::$rootDir)) {
            self::$rootDir = $dir;
        }
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file !== '.' && $file !== '..') {
                $fullPath = $dir . '/' . $file;
                if (!is_dir($fullPath)) {
                    if (in_array($fullPath, $filterFile)) {
                        continue;
                    }
                    @unlink($fullPath);
                } else {
                    if (in_array($fullPath, $filterDir)) {
                        continue;
                    }
                    self::emptyDir($fullPath, $isDelete);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if ($dir !== self::$rootDir) {
            return self::delDir($dir);
        } else {
            return $isDelete ? self::delDir($dir) : true;
        }
    }

    /**
     * 删除目录
     *
     * @param string $dir
     *
     * @return bool
     * @author lengbin(lengbin0@gmail.com)
     */
    private static function delDir($dir)
    {
        return @rmdir($dir) ? true : false;
    }

    /**
     * 递归修改目录/文件权限
     *
     * @param string $path  路径
     * @param int    $chmod 权限
     *
     * @return bool
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function chmod($path, $chmod)
    {
        if (!is_dir($path)) {
            return @chmod($path, $chmod);
        }
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $fullPath = $path . '/' . $file;
                if (is_link($fullPath)) {
                    return false;
                } elseif (!is_dir($fullPath) && !@chmod($fullPath, $chmod)) {
                    return false;
                } elseif (!self::chmod($fullPath, $chmod)) {
                    return false;
                }
            }
        }
        closedir($dh);
        if (@chmod($path, $chmod)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 复制文件夹/文件
     *
     * @param string  $src        源路径
     * @param string  $dst        复制路径
     * @param array   $filterDir  过滤文件夹
     * @param array   $filterFile 过滤文件
     * @param boolean $isUnlink   是否删除
     *
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function copyDir($src, $dst, array $filterDir = [], array $filterFile = [], $isUnlink = false)
    {
        $dir = opendir($src);
        self::pathExists($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..') && !in_array($file, $filterDir)) {
                if (is_dir($src . "/" . $file)) {
                    self::copyDir($src . "/" . $file, $dst . "/" . $file, $filterDir, $filterFile, $isUnlink);
                } else {
                    if (!in_array($file, $filterFile)) {
                        @copy($src . "/" . $file, $dst . "/" . $file);
                        if ($isUnlink) {
                            @unlink($src . "/" . $file);
                        }
                    }
                }
            }
        }
        closedir($dir);
    }

    /**
     * 目录下是否有文件
     *
     * @param $path
     *
     * @return bool
     *
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function dirExistFile($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        $files = scandir($path);
        // 删除  "." 和 ".."
        unset($files[0]);
        unset($files[1]);
        // 判断是否为空
        if (!empty($files[2])) {
            return true;
        }
        return false;
    }
	/**
	 * 创建深层目录
	 *
	 * @param string $dir  路径
	 * @param int    $mode 权限模式
	 *
	 * @return bool
	 */
	public static function mkdirDeep(string $dir, int $mode = 0766): bool
	{
		if ($dir == '') {
			return false;
		} elseif (is_dir($dir) && @chmod($dir, $mode)) {
			return true;
		} elseif (@mkdir($dir, $mode, true)) { //第三个参数为true即可以创建多级目录
			return true;
		}

		return false;
	}
	/**
	 * 遍历路径获取文件树
	 *
	 * @param string $path      路径
	 * @param string $type      获取类型:all-所有,dir-仅目录,file-仅文件
	 * @param bool   $recursive 是否递归
	 *
	 * @return array
	 */
	public static function getFileTree(string $path, string $type = 'all', bool $recursive = true): array
	{
		$path = rtrim($path, DIRECTORY_SEPARATOR);
		$tree = [];
		// '{.,*}*' 相当于 '.*'(搜索.开头的隐藏文件)和'*'(搜索正常文件)
		foreach (glob($path . '/{.,*}*', GLOB_BRACE) as $single) {
			if (is_dir($single)) {
				$file = str_replace($path . '/', '', $single);
				if ($file == '.' || $file == '..') {
					continue;
				}

				if ($type != 'file') {
					array_push($tree, $single);
				}

				if ($recursive) {
					$tree = array_merge($tree, self::getFileTree($single, $type, $recursive));
				}
			} elseif ($type != 'dir') {
				array_push($tree, $single);
			}
		}

		return $tree;
	}
	/**
	 * 获取目录大小,单位[字节]
	 *
	 * @param string $path
	 *
	 * @return int
	 */
	public static function getDirSize(string $path): int
	{
		$size = 0;
		if ($path == '' || !is_dir($path)) {
			return $size;
		}

		$dh = @opendir($path); //比dir($path)快
		while (false != ($file = @readdir($dh))) {
			if ($file != '.' and $file != '..') {
				$fielpath = $path . DIRECTORY_SEPARATOR . $file;
				if (is_dir($fielpath)) {
					$size += self::getDirSize($fielpath);
				} else {
					$size += filesize($fielpath);
				}
			}
		}
		@closedir($dh);
		return $size;
	}
	/**
	 * 批量改变目录模式(包括子目录和所属文件)
	 *
	 * @param string $path     路径
	 * @param int    $filemode 文件模式
	 * @param int    $dirmode  目录模式
	 */
	public static function chmodBatch(string $path, int $filemode = 0766, int $dirmode = 0766): void
	{
		if ($path == '') {
			return;
		}

		if (is_dir($path)) {
			if (!@chmod($path, $dirmode)) {
				return;
			}
			$dh = @opendir($path);
			while (($file = @readdir($dh)) !== false) {
				if ($file != '.' && $file != '..') {
					$fullpath = $path . '/' . $file;
					self::chmodBatch($fullpath, $filemode, $dirmode);
				}
			}
			@closedir($dh);
		} elseif (!is_link($path)) {
			@chmod($path, $filemode);
		}
	}
	/**
	 * 清空目录(删除目录下所有文件,仅保留当前目录)
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function clearDir(string $path): bool
	{
		if (empty($path) || !is_dir($path)) {
			return false;
		}

		$dirs     = [];
		$dir      = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);

		foreach ($iterator as $single => $file) {
			$fpath = $file->getRealPath();
			if ($file->isDir()) {
				array_push($dirs, $fpath);
			} else {
				//先删除文件
				@unlink($fpath);
			}
		}

		//再删除目录
		rsort($dirs);
		foreach ($dirs as $dir) {
			@rmdir($dir);
		}

		unset($objects, $object, $dirs);
		return true;
	}
	/**
	 * 格式化路径字符串(路径后面加/)
	 *
	 * @param string $dir
	 *
	 * @return string
	 */
	public static function formatDir(string $dir): string
	{
		if ($dir == '') {
			return '';
		}

		$order   = [
			'\\',
			"'",
			'#',
			'=',
			'`',
			'$',
			'%',
			'&',
			';',
			'|'
		];
		$replace = [
			'/',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
		];

		$dir = str_replace($order, $replace, $dir);
		return rtrim(preg_replace(RegularHelper::$patternDoubleSlash, '/', $dir), ' /　') . '/';
	}


}
