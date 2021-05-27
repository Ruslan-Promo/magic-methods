<?php

class Demo{
    protected $link;
    private $login, $password;
    public $title;
    public $data = [];

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->title = $login;
        $this->connect();
    }

    public function __destruct() {
        print "Уничтожается " . __CLASS__  . "\n";
    }

    private function connect()
    {
        $this->link = 'https://google.com/?login='.$this->login.'&password'. $this->password;
    }

    public function __sleep()
    {
        return array('login', 'password');
    }

    public function __wakeup()
    {
        $this->connect();
    }

    public function __serialize(): array
    {
        return [
          'login' => $this->login,
          'pass' => $this->password,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->login = $data['login'];
        $this->password = $data['pass'];

        $this->connect();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function __invoke($x)
    {
        var_dump($x);
    }

    public static function __set_state($an_array)
    {
        $obj = new Demo('test', 'test');
        $obj->var1 = $an_array['var1'];
        $obj->var2 = $an_array['var2'];
        return $obj;
    }

    public function __debugInfo() {
        return [
            'test' => $this->title.' debug',
        ];
    }

    public function __call($name, $arguments) {
        echo "Вызов метода '$name' ". "\n";
    }

    public static function __callStatic($name, $arguments) {
        echo "Вызов статического метода '$name' ". "\n";
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {        
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

}

$demo = new Demo('userlogin', 'userpassword');
// __serialize() и __sleep(), если есть __serialize(), то __sleep() игнорируется
$result = $demo->serialize();
print_r($result);

// __unserialize() и __wakeup(), если есть __unserialize(), то __wakeup() игнорируется
$result = $demo->unserialize(['login'=>'Login', 'pass' => 'Password']);
print_r($result);

// __toString()
echo $demo;

//__invoke()
$demo(2);
print_r($demo);

//__set_state()
$state = var_export($demo, true);
print_r($state);

//__debugInfo()
var_dump(new Demo('userlogin', 'userpassword'));

//__call() и __callStatic()
$demo->test('Test');
Demo::test('Test');

//__set() и __get()
$demo->set = 'ok';
$get = $demo->set;

//__isset()
print_r(isset($demo->set));

//__unset()
unset($demo->set);
print_r(isset($demo->set));

//__destruct()
exit();