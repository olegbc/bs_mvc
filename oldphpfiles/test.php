<?php 

	// $sql = "SELECT `num_payed` FROM `payed_lessons` WHERE `id_person`=".$id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
	// $result = mysql_query($sql)	or die(mysql_error());
	// $row=mysql_fetch_array($result);
	// $num=$row[0];

	// $sql = "SELECT `discount` FROM `discounts` WHERE `id_person` ='".$person."' AND `teacher` = '".$teacher."' AND `level_start` = '".$level_start."' AND 
	// 	`timetable` ='".$timetable."'";
	// $result = mysql_query($sql)	or die(mysql_error());
	// $row = mysql_fetch_row($result);
	// $discount = $row[0];

	// $sql = "SELECT `one lesson default` FROM `constants`" ;
	// $result = mysql_query($sql) or die(mysql_error());
	// $row = mysql_fetch_row($result);
	// $one_lesson_default = $row[0];
	// $one_lesson =$one_lesson_default - round(($one_lesson_default*($discount*0.01)),2);

	// $back_to_balance=$num*$one_lesson;

	// $sql="UPDATE `balance` SET `balance`= balance +".$back_to_balance." WHERE `id_person`=".$id." AND `teacher`='".$teacher."' AND `timetable`='".$timetable."' AND `level_start`='".$level_start."'";
	// $result=mysql_query($sql) or die(mysql_error());

header('Content-Type: text/html; charset=utf-8');

	// class TextClass{
	// 	public $public_var = 4;
	// 	private $private_var = 7;
	// 	protected $protected_var = 9;

	// 	public  function getVar(){
	// 		echo $this->public_var."<br>"; 
	// 		echo $this->private_var."<br>"; 
	// 		echo $this->protected_var."<br>"; 
	// 	}
	// 	public  function get_protected_po(){
	// 		$this->protected_po();
	// 	}
	// 	public  function get_private_po(){
	// 		$this->private_po();
	// 	}
	// 	public  function public_po(){
	// 		echo "POPO";
	// 	}
	// 	protected  function protected_po(){
	// 		echo "POPO";
	// 	}
	// 	private  function private_po(){
	// 		echo "POPO";
	// 	}
	// }

	// class Child extends TextClass{
	// 	public function getVarChild(){
	// 		echo $this->protected_var.PHP_EOL;
	// 	}
	// 	public function get_protected_po_from_parent(){
	// 		parent::protected_po();
	// 	}
	// 	public function get_private_po_from_parent(){
	// 		parent::private_po();
	// 	}
	// }

	// $sauron_eye = new ReflectionClass('TextClass');
	// $all_properties = $sauron_eye->getDefaultProperties();

	// foreach($all_properties as $key=>$value){
	// 	echo $key." is ".$value."<br>";
	// }

	// $obj = new TextClass();
	// var_dump($obj);
	// $obj2 = new Child();
	// $obj2->get_private_po_from_parent();
	// $obj3 = new GrandChild();
	// $obj->getVar();
	// echo $obj2->getVarChild();
	// echo $obj3->getVarGrandChild();

	// class Member {

	//  private $username;
	//  private $location;
	//  private $homepage;

	//  public function __construct( $username, $location, $homepage ) {
	//    $this->username = $username;
	//    $this->location = $location;
	//    $this->homepage = $homepage;
	//  }
	//    public function __destruct() {
	//    echo "I'm about to disappear - bye bye!";
	//    // (очистить память)
	//  }
	//  public function showProfile() {
	//    echo "<dl>";
	//    echo "<dt>Username:</dt><dd>$this->username</dd>";
	//    echo "<dt>Location:</dt><dd>$this->location</dd>";
	//    echo "<dt>Homepage:</dt><dd>$this->homepage</dd>";
	//    echo "</dl>";
	//  }
	// }

	// $aMember = new Member( "fred", "Chicago", "http://example.com/" );
	// $aMember->showProfile();

	// $aMember2 = new Member( "fred2", "Chicago2", "http://example.com/2" );
	// $aMember2->showProfile();
	// $aMember->showProfile();
	// unset($aMember);
	// $aMember->showProfile();

	// class Member{
	// 	const MEMBER = 1;
	// 	const MODERATOR = 2;
	// 	const ADMINISTRATOR = 3;

	// 	private $username;
	// 	public static $numMembers = 0;

	// 	public function __construct( $username ) {
	// 		$this->username = $username;
	// 		self::$numMembers++;
	// 		}
	// 	public static function change(){
	// 		// self::MEMBER = 45;
	// 	}
	// }

	// echo Member::$numMembers . "<br>";  
	// $aMember = new Member( "fred" );
	// echo Member::$numMembers . "<br>";  
	// $anotherMember = new Member( "mary" );
	// echo Member::$numMembers . "<br>"; 
	// Member::change();	
	// Member::MEMBER = 45;
	// $member = 'Member';
	// echo $member::MEMBER;
	// print Member::MEMBER;
	// Member::$numMembers++;

	// echo Member::$numMembers+1 . "<br>"; 

	// echo Member::$numMembers = 7 . "<br>"; 
	// echo Member::$numMembers  . "<br>";

// 	class MyClass
// {
//     private static $staticVariable = null;

//     public static function showStaticVariable($value = null)
//     {
//         if ((is_null(self::$staticVariable) === true) && (isset($value) === true))
//         {
//             self::$staticVariable = $value;
//         }

//         return self::$staticVariable;
//     }
// }

// print MyClass::showStaticVariable()\n; // null
// print MyClass::showStaticVariable('constant value'); // "constant value"
// print MyClass::showStaticVariable('other constant value?'); // "constant value"
// print MyClass::showStaticVariable(); // "constant value" 

// class Member {

//  private $username;
//  private $data = array();

//  public function Get( $property ) {
//    if ( $property == "username" ) {
//      return $this->username;
//      echo 1;
//    } else {
//      if ( array_key_exists( $property, $this->data ) ) {
//        return $this->data[$property];
//        echo 2;
//      } else {
//        return null;
//        echo 3;
//      }
//    }
// }
	
// public function Set( $property, $value ) {
//    if ( $property == "username" ) {
//      $this->username = $value;
//    } else {
//      $this->data[$property] = $value;
//    }
//  }
// }

// $aMember = new Member();
// $aMember->Set('username','fred');
// $aMember->Get($user);
// $aMember->Set($user);
// echo $aMember->Get('username');
// echo $aMemver-
// $aMember->location = "San Francisco";
// echo $aMember->username . "<br>";  // отобразит "fred"
// echo $aMember->location . "<br>";  // отобразит "San Francisco"
// $aMember->gogo = 'yyyy'. "<br>";
// print $aMember->gogo;

// class Par{
// 	public $var = 'variable';
// }
// class Child{

// }
// $obj=new Child();
// $obj->var;

// class Singleton
// {
//     /**
//      * Returns the *Singleton* instance of this class.
//      *
//      * @staticvar Singleton $instance The *Singleton* instances of this class.
//      *
//      * @return Singleton The *Singleton* instance.
//      */
//     public static function getInstance()
//     {
//         static $instance = null;
//         // if (null === $instance) {
//         if ($instance === null) {
//             $instance = new static();
//         }

//         return $instance;
//     }

//     /**
//      * Protected constructor to prevent creating a new instance of the
//      * *Singleton* via the `new` operator from outside of this class.
//      */
//     protected function __construct()
//     {
//     }

//     /**
//      * Private clone method to prevent cloning of the instance of the
//      * *Singleton* instance.
//      *
//      * @return void
//      */
//     private function __clone()
//     {
//     }

//     /**
//      * Private unserialize method to prevent unserializing of the *Singleton*
//      * instance.
//      *
//      * @return void
//      */
//     private function __wakeup()
//     {
//     }
// }

// class SingletonChild extends Singleton
// {
// }
// class Mem{}


// $obj = Singleton::getInstance();
// $obj2 = Singleton::getInstance();
// $obj3 = new Mem();
// echo gettype($obj).'<br>';
// $test='';
// ($obj === $obj2)?$test='yes':$test='no';	
// ?$test='yes':$test='no';	
// echo $test;
// var_dump($obj === Singleton::getInstance());             // bool(true)

// $anotherObj = SingletonChild::getInstance();
// var_dump($anotherObj === Singleton::getInstance());      // bool(false)

// var_dump($anotherObj === SingletonChild::getInstance()); // bool(true)


  
class Member
{
  public $username = "";
  private $loggedIn = false;
  
  public function login() {
    $this->loggedIn = true;
  }
  
  public function logout() {
    $this->loggedIn = false;
  }
  
  public function isLoggedIn() {
    return $this->loggedIn;
  }
 
  public function __sleep() {
    echo "Cleaning up the object...<br>";
    // return array( "username" );
    // print_r(array_keys( get_object_vars( $this )));
    // print_r(get_object_vars( $this ));
    return array_keys( get_object_vars( $this ) );

  }
 
  public function __wakeup() {
    echo "Setting up the object...<br>";
  }
 
}
 
$member = new Member();
$member->username = "Fred";
$member->login();
 
$memberString = serialize( $member );
echo "Converted the Member object to a string: '$memberString'<br>";
echo "Converting the string back to an object...<br>";
$member2 = unserialize( $memberString );
echo $member2->username . " is " . ( $member2->isLoggedIn() ? "logged in" : "logged out" ) . "<br>";
  




