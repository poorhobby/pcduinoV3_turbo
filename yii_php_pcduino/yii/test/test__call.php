<?php
class A 
{
	public $xxx = "abcdefg";
	const CCC ='xxx123';
	public function __call($method, $p)
	{
		if ($method == "display")
		{
			echo "reload display\n";
		}
	}
	public function display()
	{
		echo "display\n";
	}
	public function __toString()
	{
		return (var_export($this, TRUE));
	}
}



$obj = new A;
$obj->display();
echo($obj->__toString());


$class = new ReflectionClass("A");
echo "<pre>".$class."</pre>";
?>