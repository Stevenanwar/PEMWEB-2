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
        foreach ($this->animal as $animal) {
            echo "- $animal <br/>";
        }
    }

    public function store($animal)
    {
        $this->animal[] = $animal;
    }

    public function update($index, $animal)
    {
        $this->animal[$index] = $animal;
    }

    public function destroy($index)
    {
        unset($this->animals[$index]);
    }
}

$animal = new Animal(['Ayam', "Ikan"]);

echo "Index - Menampilkan seluruh hewan <br/>";
$animal->index();
echo "<br/>";

echo "Store - Menambahkan seluruh hewan (burung) <br/>";
$animal->store("Burung");
$animal->index();
echo "<br/>";

echo "Update - Mengupdate hewan <br/>";
$animal->update(0, "Kucing Garong");
$animal->index();
echo "<br/>";

echo "Destroy - Menghapus hewan <br/>";
$animal->destroy(1);
$animal->index();
echo "<br/>";
