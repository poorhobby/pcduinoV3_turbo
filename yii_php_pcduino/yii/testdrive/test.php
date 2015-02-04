<?php
class AB
{
	private $abcd;
	public function eafea()
	{
		print "213124";
	}
	public function run()
	{
		return $arr = array($this, 'eafea');
	}
	
};

$b = array('abcd'=>'123', 'efgh'=>'456');
foreach ($b as $key => $value) {
	print $key;
	print $value;
}


$a = new AB();
print $a->name;
$c = $a->run();
call_user_func($c);

?>