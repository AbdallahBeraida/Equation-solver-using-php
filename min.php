<html>
<body bgcolor="black" background="a.jpg">
  <font color="white" size ="5">
       <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   
   <title>GES</title>
<font color="white" size ="8"
face="Ariel">
<b>General Equation Solver</b>
</font>
   <div id='cssmenu'>
<ul>
   <li><a href='mini.html'><span>Home</span></a></li>
   <li><a href='Equations.html'><span>Equations</span></a></li>
   <li><a href='guideline.html'><span>Guidelines</span></a></li>
   <li><a href='contacts.html'><span>Contact</span></a></li>
   <li class='last'><a href='about.html'><span>About</span></a></li>
</ul>
</div>





<?php





$eq = $_POST['eq']; 
//echo "The entered equation is <br> ".$eq;
$part=explode('=',$eq);
$eq1=$part[0];


$symbol = '+-';

$equ1 = str_replace('-', "$symbol", $eq1);
//echo "<br>" .$equ. "<br>";
$eq2=$part[1];
$pos1 = strpos($eq2,"-");
if($pos1!=1)
{
  $eq2='+'.$eq2;
}

//echo $eq2;
$symbol= '*';
$eq2= str_replace('-', "$symbol", $eq2);
$symbol= '+-';
$eq2= str_replace('+', "$symbol", $eq2);

$symbol= '+';
$equ2= str_replace('*', "$symbol", $eq2);
//echo "<br>".$equ2."<br>";
$equ=$equ1.$equ2;
//echo $equ;


     


  $max= substr_count($equ, '+', 0)+1;
//echo $max;


  $parts=explode('+',$equ);
  $var=mysql_connect("localhost","root","athul") or die(mysql_error());mysql_select_db("mini",$var);
  $sql="DELETE FROM `cal1` ";
    mysql_query($sql,$var);

  $sql="ALTER TABLE cal1 AUTO_INCREMENT = 1";
    mysql_query($sql,$var);
    $sql="DELETE FROM `cal` ";
    mysql_query($sql,$var);

  $sql="ALTER TABLE cal AUTO_INCREMENT = 1";
    mysql_query($sql,$var);
    $sql="DELETE FROM `sol` ";
    mysql_query($sql,$var);

  $sql="ALTER TABLE sol AUTO_INCREMENT = 1";
    mysql_query($sql,$var);

//--------------------------------- inserting into table----------------------------

for ($x = 0; $x < $max; $x++) {
    
    //echo "<br>".'equation '.$x.'is  '.$parts[$x]."<br>";
 

     $string = $parts[$x];
     $pos1 = strpos($string, "-");
    // echo $string;
    $pos = strpos($string, "x");
    
    
      if ($pos === false) {
       $sql="INSERT INTO cal1 (`coeff1`, `degree1`) VALUES ('$string','0')";
        mysql_query($sql,$var);
       //echo "<br>Not found<br>";
        //add constant from rhs
        //echo $parts[$x]."<br>";
    } else {
        
   // echo "<br> $pos<br>";

    if($pos===0)
    {

      $symbol = '1';

    $parts[$x]=$symbol.$string;
    //echo "<br>".$parts[$x];
    }
    else if(($pos===1)&&($pos1===0))
    {
        $symbol = '-1';

    $parts[$x]=$symbol.$string;
    }
     $p1 = strpos($parts[$x], "^");
    if($p1===false)
    {
      $symbol= '^1';

    $parts[$x]=$parts[$x].$symbol;

    //echo "<br>".$parts[$x];





    }
    $parts1=explode('x^',$parts[$x]);
   
    
    
   // echo "<br>".'the coeff is'.$parts1[0]."<br>";
    $tmp=$parts1[0];
    
   // echo "<br>".'the degree is'.$parts1[1]."<br>";
    $tmp1=$parts1[1];
$sql="INSERT INTO cal1 (`coeff1`, `degree1`) VALUES ('$tmp','$tmp1')";
    mysql_query($sql,$var);





      
    }
  
}


//----------------------------inserting into table end----------------------


//---------------------------------addition----------------

 $result = mysql_query("SELECT * FROM cal1",$var);//count number of rows

$n= mysql_num_rows($result);
//echo $n;

for ($x=1; $x <=$n; $x++)
{

  $val=mysql_query("SELECT degree1 FROM cal1 WHERE num='$x'");
$tmp=mysql_fetch_array($val);


$val=mysql_query("SELECT coeff1 FROM cal1 WHERE num='$x'");
$tmp1=mysql_fetch_array($val);


for ($y=$x+1; $y<=$n; $y++)
{
   
  
  
$val=mysql_query("SELECT degree1 FROM cal1 WHERE num='$y'");
$tmp2=mysql_fetch_array($val);

$val=mysql_query("SELECT coeff1 FROM cal1 WHERE num='$y'");
$tmp3=mysql_fetch_array($val);

if($tmp[0]==$tmp2[0])
{$tmp1[0]=$tmp1[0]+$tmp3[0];
  $sql="UPDATE cal1 SET `coeff1` = '0' WHERE `cal1`.`num` = $y";
mysql_query($sql,$var);
  
//echo $y;



}
}
$s= $tmp1[0];

$sql="UPDATE cal1 SET coeff1 = '$s' WHERE `cal1`.`num` = $x";
mysql_query($sql,$var);

//echo $s



}
$sql="DELETE FROM cal1 WHERE coeff1= '0' ";  
mysql_query($sql,$var);  
$sql="SELECT * FROM cal1 ORDER BY degree1  ";  
mysql_query($sql,$var); 

$sql="ALTER TABLE cal1  DROP num ";  
mysql_query($sql,$var);  

$sql="ALTER TABLE `cal1` ADD `num` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";  
mysql_query($sql,$var);  


//-----------------------addition end-------------------

//----------------------------------higher order------------


//-------------------------------------------
c:
$val="SELECT max(degree1) AS m FROM cal1 ";  
$result = mysql_query($val);
$l = mysql_fetch_assoc($result);
//$count=0;//delete
$n=$l['m'];
$r="";
if($n>2)
{
$val=mysql_query("SELECT coeff1 FROM cal1 WHERE degree1='0'");
$con=mysql_fetch_array($val);
$lim=$con[0];
$lim=abs($lim);
$ch=0;


$result = mysql_query("SELECT coeff1 FROM cal1");
$tmp1= Array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $tmp1[] =  $row['coeff1'];  

}
$result = mysql_query("SELECT degree1 FROM cal1");
$tmp= Array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $tmp[] =  $row['degree1'];  
}
k:
$j=0;
$l=0;


$result = mysql_query("SELECT * FROM cal1",$var);//count number of rows

$n= mysql_num_rows($result);

for($x=0;$x<$n;$x++)
{
  $cl=0-$ch;
  //echo $ch;

 //echo $tmp[$x];
  //echo "<br>";
  $j=$j+$tmp1[$x]*pow($ch,$tmp[$x]);
  //echo $j;
  //echo "<br>";
   $l=$l+$tmp1[$x]*pow($cl,$tmp[$x]);
}

if($j==0)
{

 $sql="INSERT INTO `cal`(`coeff1`, `degree1`) VALUES ('1','1')";
  mysql_query($sql,$var);
$sql="INSERT INTO `cal`(`coeff1`, `degree1`) VALUES ('$cl','0')";
  mysql_query($sql,$var);

  echo $ch."<br>";
}

else if($l==0)
{
  
  $sql="INSERT INTO `cal`(`coeff1`, `degree1`) VALUES ('1','1')";
  mysql_query($sql,$var);
$sql="INSERT INTO `cal`(`coeff1`, `degree1`) VALUES ('$ch','0')";
  mysql_query($sql,$var);

echo $cl."<br>";
}

else{

  $ch++;
  if($ch>$lim)
  {
    echo " no other integer roots";
    goto l;
  }

  goto k;


}



 b:


$val="SELECT max(degree1) AS m FROM cal ";  
$result = mysql_query($val);
$l = mysql_fetch_assoc($result);

$d2=$l['m'];
$val=mysql_query("SELECT coeff1 FROM cal WHERE degree1=$d2");
$c2=mysql_fetch_array($val);


    

$val="SELECT max(degree1) AS m FROM cal1 ";  
$result = mysql_query($val);
$l = mysql_fetch_assoc($result);

$d1=$l['m'];
$val=mysql_query("SELECT coeff1 FROM cal1 WHERE degree1=$d1");
$c1=mysql_fetch_array($val);


$c=$c1[0]/$c2[0];
$d=$d1-$d2;


  $sql="INSERT INTO `sol`(`coeff1`, `degree1`) VALUES ('$c','$d')";
        mysql_query($sql,$var);


//--------------------multiplication


        $result = mysql_query("SELECT * FROM cal",$var);//count number of rows

$n= mysql_num_rows($result);
    for ($x = 1; $x <=$n ; $x++) {
   $val=mysql_query("SELECT degree1 FROM cal WHERE num='$x'");
$d3=mysql_fetch_array($val);


$val=mysql_query("SELECT coeff1 FROM cal WHERE num='$x'");
$c3=mysql_fetch_array($val);

$d2=$d+$d3[0];

$c2=$c*$c3[0];
$c2=-$c2;
 $sql="INSERT INTO `cal1`(`coeff1`, `degree1`) VALUES ('$c2','$d2')";
  mysql_query($sql,$var);

}



//---------------------addition----------
 
  $result = mysql_query("SELECT * FROM cal1",$var);//count number of rows

$n= mysql_num_rows($result);

for ($x = 1; $x <=$n; $x++)
{

  $val=mysql_query("SELECT degree1 FROM cal1 WHERE num='$x'");
$tmp=mysql_fetch_array($val);


$val=mysql_query("SELECT coeff1 FROM cal1 WHERE num='$x'");
$tmp1=mysql_fetch_array($val);


for ($y=$n; $y>$x; $y--)
{
   
  
  
$val=mysql_query("SELECT degree1 FROM cal1 WHERE num='$y'");
$tmp2=mysql_fetch_array($val);

$val=mysql_query("SELECT coeff1 FROM cal1 WHERE num='$y'");
$tmp3=mysql_fetch_array($val);

if($tmp[0]==$tmp2[0])
{$tmp1[0]=$tmp1[0]+$tmp3[0];
  $sql="UPDATE cal1 SET `coeff1` = '0' WHERE `cal1`.`num` = $y";
mysql_query($sql,$var);
  
//echo $y;



}
}
$s= $tmp1[0];
//echo $s

$sql="UPDATE cal1 SET `coeff1` = '$s' WHERE `cal1`.`num` = $x";
mysql_query($sql,$var);

}
  $sql="DELETE FROM cal1 WHERE coeff1= '0' ";  
mysql_query($sql,$var);  

$sql="ALTER TABLE cal1  DROP num ";  
mysql_query($sql,$var);  

$sql="ALTER TABLE `cal1` ADD `num` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";  
mysql_query($sql,$var);  

   $result = mysql_query("SELECT * FROM cal1",$var);//count number of rows

$y= mysql_num_rows($result);


   
if($y>0)
  {goto b;}



$sql="INSERT INTO cal1 SELECT * from sol";
mysql_query($sql,$var);
$sql="DELETE FROM `cal` ";
    mysql_query($sql,$var);
    $sql="DELETE FROM `sol` ";
    mysql_query($sql,$var);
$sql="ALTER TABLE cal AUTO_INCREMENT = 1";
    mysql_query($sql,$var);
     $sql="ALTER TABLE sol AUTO_INCREMENT = 1";
    mysql_query($sql,$var);

goto c;

}




//------------------------------------------------

//----------------------------------higher order end---------------

//----------------------general form---------------------

$val="SELECT max(degree1) AS 'm' FROM cal1 ";  
$result = mysql_query($val);
$l=mysql_fetch_assoc($result);

$n=$l['m'];


for($x=$n;$x>=0;$x--)
{
$sql = "SELECT num FROM cal1 WHERE degree1='$x'";
$result = mysql_query($sql);

if(mysql_num_rows($result) ==0){

  
  $sql="INSERT INTO cal1 (`coeff1`, `degree1`) VALUES ('0','$x')";
    mysql_query($sql,$var);
   
   }
}



$result = mysql_query("SELECT * FROM cal1",$var);//count number of rows
   $r='a';
      $k='';
$n= mysql_num_rows($result);
$result = mysql_query("SELECT degree1 FROM cal1 ORDER BY degree1 DESC");
$tmp = Array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $tmp[] =  $row['degree1'];  
}
$result = mysql_query("SELECT coeff1 FROM cal1 ORDER BY degree1 DESC");
$tmp1= Array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $tmp1[] =  $row['coeff1'];  
}
for($x=0;$x<$n;$x++)
{
     $k=$k.$r.'x^'.$tmp[$x].'+';
     $$r=$tmp1[$x];
$r++;


}
$k=rtrim($k, "+");
//echo $k;


//----------------------gerneral form end----------------

//-------------------------checking for general equation -----------------



$val=mysql_query("SELECT flag FROM general WHERE equation='$k'");
$tmp2=mysql_fetch_array($val);
//echo $tmp2[0];
//---------------------------------checking for general equation end---------------------

//---------------------------------evaluating conditions----------------------------------


$val=mysql_query("SELECT conditions FROM `check` WHERE flag='$tmp2[0]'");
$tmp3=mysql_fetch_array($val);

$s=$tmp3[0];
eval( '$d = (' . $s . ');' );
$val=mysql_query("SELECT cval FROM `check` WHERE conditions='$s'");
$tm=mysql_fetch_array($val);

if($d<$tm[0])
{
    $ch='-1';
    $d=abs($d);

}
else if($d>=$tm[0])
{$ch='1';}
$complex='i';
$and='and';
//echo $d."<br>";
$d=sqrt($d);
$d=round($d,3);

//echo $ch."<br>";

$val=mysql_query("SELECT expression FROM `check` WHERE flag='$tmp2[0]' AND eval='$ch'");
$tmp3= Array();
while ($row = mysql_fetch_array($val, MYSQL_ASSOC)) {
    $tmp3[] =  $row['expression'];  
  }


$n=count($tmp3);

$roots=' ';
for($x=0;$x<$n;$x++)
{
$s=$tmp3[$x];
eval( '$root = (' . $s . ');' );
$roots=$roots.$root."and";
}
$roots=rtrim($roots, "and");

echo $roots;



l:
 
 

?>




</body>
</html>
