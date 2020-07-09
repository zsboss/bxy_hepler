# 工具类
```php

        /***
        ** 模版替换  
        ** 
        */
         $content = (new Template($this->phpFile))
                    ->place('before', 1)
                    ->place('case',2 )
                    ->produce();
        
        
        /**
         * Class ZipHelper
         * 文件压缩， 解压帮助类
         *
         *  //压缩
         *  $zip = new ZipHelper();
         *  $zip->setPath('/Users/lengbin/Documents/ruby');
         *  //是否下载
         *  $zip->setIsDownload(true);
         *  // 是否删除文件（zip文件，压缩时候的文件）
         *  $zip->setIsDelete(true);
         *  // 压缩文件， 支持 文件夹， 文件， 网路文件
         *  $zip->zip('test', [
         *      '/Users/lengbin/Documents/ruby/demo',
         *      'http://www.sostudy.cn/images/newhxs/bg.jpg'
         *  ]);
         *
         *  //解压
         *  $zip = new ZipHelper();
         *  $zip->setPath('/Users/lengbin/Documents/ruby');
         *  // 是否删除zip文件
         *  $zip->setIsDelete(true);
         *  // 解压
         *  $zip->unzip('test');
         *
         * @package api\controllers
         * @author  lengbin(lengbin0@gmail.com)
         */
    
```
