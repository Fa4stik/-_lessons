<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        class Product {
            public $id;
            public $title;
            public $description;
            public $price;
            public $count;
        
            public function getDetails() {
                return "$this->id / $this->title / $this->description / $this->price / $this->count";
            }
        
            public function updateCount($quantity) {
                $this->count = $quantity;
                return $this->count;
            }
        }
        
        class DigitalProduct extends Product {
            public $download_link;
        }
        
        class PhysicalProduct extends Product {
            public $weight;
            public $dimensions;
        }
        
        class Cart {
            public $userId;
            public $items = [];
        
            public function addItem(Product $product) {
                $this->items[] = $product;
                return $this->items;
            }
        
            public function removeItem(Product $product) {
                $this->items = array_values(array_filter($this->items, function($productId) {
                    return $product->id !== $productId;
                }));
            }
        
            public function getTotalPrice() {
                return array_reduce($this->items, function($carry, $product) {
                    return $carry + $product->price;
                }, 0);
            }
        }
        
        class Review {
            public $id;
            public $productId;
            public $userId;
            public $content;
            public $rating;
        
            public function publish() {
                return "Откзыв №$this->id для продукта №$this->productId \n$this->content\nРейтинг: $this->rating";
            }
        
            public function editContent($newContent) {
                $this->content = $newContent;
                return $this->content;
            }
        }
        
        class User {
            public $id;
            public $username;
            public $password;
            public $email;
        
            public function login($username, $password) {
                if ($username == $this->username && $password == $this->password) {
                    return 1;
                }
                return 0;
            }
        
            public function logout() {
                $this->id = "";
                $this->username = "";
                $this->password = "";
                $this->email = "";
            }
        
            public function register($id, $username, $password, $email) {
                $this->id = $id;
                $this->username = $username;
                $this->password = $password;
                $this->email = $email;
            }
        }
        
        class FeedbackForm {
            public $id;
            public $user_id;
            public $content;
        
            public function submit() {
                return "Отправлено: $this->id\n$this->user_id\n$this->content\n";
            }
        }
    ?>
</body>
</html>