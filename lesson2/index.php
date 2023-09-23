<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        abstract class Product
        {
            protected $price;
        
            public function __construct($price)
            {
                $this->price = $price;
            }
        
            abstract public function getFinalPrice();
        }
        
        class DigitalProduct extends Product
        {
            public function getFinalPrice()
            {
                return $this->price / 2;
            }
        }
        
        class IndividualProduct extends Product
        {
            protected $quantity;
        
            public function __construct($price, $quantity)
            {
                parent::__construct($price);
                $this->quantity = $quantity;
            }
        
            public function getFinalPrice()
            {
                return $this->price * $this->quantity;
            }
        }
        
        class WeightedProduct extends Product
        {
            protected $weight;
        
            public function __construct($price, $weight)
            {
                parent::__construct($price);
                $this->weight = $weight;
            }
        
            public function getFinalPrice()
            {
                return $this->price * $this->weight;
            }
        }

        $digitalProduct = new DigitalProduct(20);
        echo $digitalProduct->getFinalPrice();
        
        $individualProduct = new IndividualProduct(20, 3);
        echo "\n".$individualProduct->getFinalPrice();

        $weightedProduct = new WeightedProduct(20, 4.5);
        echo "\n".$weightedProduct->getFinalPrice();
    ?>
</body>
</html>