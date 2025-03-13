<?php
class animal
{
    public $animal;

    public function __construct($ar_animal)
    {
        $this->animal = $ar_animal;
    }
    public function index()
    {
        foreach ($this->animal as $animal){
            echo " - $animal </br>";
        }
    }

    public function store($animal)
    {
        $this->animal[] = $animal;
    }
    public function update($index,$animal)
    {
        $this->animal[$index] = $animal;
    }



    public function destory($index) 
    {
        unset($this->animal[$index]);
    }
}
$animal = new animal(['ayam', 'ikan']);


echo "Index - Menampilkan seluruh hewan <br/>";
$animal->index();
echo "</br>";

echo "store - Menambahkan hewan baru(burung) <br/>";
$animal->store("burung");
$animal->index();
echo "</br>";

echo "update - Mengupdate hewan <br/>";
$animal->update(0,"kucing anggora");
$animal->index();
echo "</br>";

echo "destory - Menghapus hewan <br/>";
$animal->destory(1);    
$animal->index();
echo "</br>";
