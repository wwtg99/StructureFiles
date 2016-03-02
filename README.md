# StructureFiles
### A common interface for Section structure files and wrappers for downloading and printing in browser.

#### Section structure
- section name, optional
- section head, optional
- section body, required

#### Section type
- key value (kv)
```
key1 value1
key2 value2
```
- Table (tsv)
```
field1 field2 field3
value1 value2 value3
value4 value5 value6
```
- Raw 
  two dimension array
  
#### Examples
```
$name = 'name1';
// body [['field1'=>'', 'field2'=>'', ...], ...]
$data = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>'v3', 'f2'=>'v4']];
// head [['title'=>'', 'field'=>'', 'type'=>''], ...]
$head = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
// rule
$rules = [];
$s = new Section(Section::KV_SECTION, $name, $data, $head, $rules);
// SectionFile is a list of Section
$sf = new SectionFile([$s1]);
```

##### Rules
* showHead: bool, default true
* showName: bool, default true
* null: string, default '-', string to show if value is null
* skip: array, default [], skip fields
* prefix: string, default '', prefix added before each line
* postfix: string, default '', postfix added after each line
* del: string, default '\t', delimiter between each fields

##### Use SectionFile class to create txt file or excel file
```
$txt1 = TxtFile::createFromSection($sf);
$excel1 = ExcelFile::createFromSection($sf);
// Download in browser
$excel1->download();
// Print to browser if supported
$txt1->printContent();
```
