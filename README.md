# CsvExporter
 A lightweight and concise csv exporter.  
 一个轻量、简洁的Csv导出器。
### Install
```bash
composer require xihongke/csv-exporter
```
### Usage
```php
use XihongKe\CsvExporter\CsvExporter;

$exporter = new CsvExporter("学生列表", ['学号', '姓名', '性别']);

// 往表里写入单行数据
$exporter->row(['1001', '张三', '男']);
// 多行数据
$exporter->rows([
    ['1002', '李红', '女'],
    ['1003', '吴均', '男'],
]);
```
