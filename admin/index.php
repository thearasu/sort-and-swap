<?php
$products = file_get_contents("../products.json"); //Get products data from the products.json file

//If any value given by POST method store it on $products and also put it on products.json
if($_POST && $_POST["products"]){
    global $products;
    $products = $_POST["products"];
    file_put_contents("../products.json",$products);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
    <title>Ecom Admin</title>
    <style>
    .hide{
    display: none;
    }
    </style>
</head>
<body>
    <div class="container p-0">
        <header class="navbar bg-secondary px-1">
            <section class="navbar-center">
                <b class="h5">Ecom Admin</b>
            </section>
            <section class="navbar-section">
                <button class="btn btn-primary m-1" onClick="window.location.reload()">Reload</button>
                <a href="../" class="btn btn-primary m-1">View site</a>
            </section>
        </header>
        <div class="columns m-2">
            <div class="col-8 col-mx-auto">
            <h4>Products</h4>
            <div id="products-list" class="col-12 col-mx-auto">
                <!-- javascript deals with it -->
            </div>
            <div id="add-product-form" class="card p-1 mx-2 hide">
                <form id="add-new-form" class="form-horizontal" onSubmit="addNewProduct(event)">
                    <div class="form-group">
                        <div class="col-2 col-sm-12">
                            <label class="form-label" for="title"><b>Product Title</b></label>
                        </div>
                        <div class="col-10 col-sm-12">
                            <input class="form-input" name="title" type="text" placeholder="The Product Tetra" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2 col-sm-12">
                            <label class="form-label" for="price"><b>Product Price</b></label>
                        </div>
                        <div class="col-10 col-sm-12">
                            <input class="form-input" name="price" type="text" placeholder="999" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2 col-sm-12">
                            <label class="form-label" for="color"><b>Choose Color</b></label>
                        </div>
                        <div class="col-10 col-sm-12">
                            <select class="form-select" name="color" required>
                                <option>black</option>
                                <option>silver</option>
                                <option>maroon</option>
                                <option>lime</option>
                                <option>navy</option>
                                <option>peru</option>
                                <option>pink</option>
                                <option>skyblue</option>
                                <option>snow</option>
                                <option>tan</option>
                                <option>plum</option>
                                <option>olive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2 col-sm-12">
                            <label class="form-label" for="sort-order"><b>Sort Order</b></label>
                        </div>
                        <div class="col-10 col-sm-12">
                            <select id="sort-order-select" class="form-select" name="sort-order" required>
                                <!-- javascript deals with it -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-2 col-sm-12">
                            <label class="form-label" for="subject"><b>Product Info</b></label>
                        </div>
                        <div class="col-10 col-sm-12">
                            <textarea class="form-input" name="subject" type="text" rows="3"></textarea>
                        </div>
                    </div>
                        <button class="btn btn-success">Add Product</button>
                </form>
            </div>
            <form id="save-changes-form" method="POST" class="p-2 hide">
                <input id="products-input"  name="products" class="hide"/>
                <button onClick="saveChanges(event)" class="btn btn-success">Save changes</button>
            </form>
            <div class="p-2">
                <button onClick="addProduct()" class="btn btn-error">+ Add New</button>
            </div>
            </div>
        </div>
    </div>
    <script>
    let products = <?php echo $products; // Will attach product data as a JSON object?>;
    const productListElement = document.getElementById("products-list")
    const saveChangesForm = document.getElementById("save-changes-form")
    const addProductForm = document.getElementById("add-product-form")
    const productsInput = document.getElementById("products-input")
    let dragStartIndex // To remember the element that is dragged
    const listProducts = [] // To store the product list elements
    createList()

    //To insert the products to the DOM
    function createList(){
        for(let product in products){
        const productElement = document.createElement('div')
        productElement.setAttribute("data-index",product)
        productElement.setAttribute("class","draggable-list p-2")
        productElement.innerHTML = `
        <div class="draggable card " draggable="true">
            <div class='card-header columns'>
            <div class='card-title col-10 h6'>${Number(product)+1}. ${products[product].title}</div>
            <div class='col-2' style='height:1rem;width:1rem;background-color:${products[product].color};'></div>
            <div class='text-primary col-12'><b>₹${products[product].price}</b></div></div>
        </div>`
        listProducts.push(productElement)
        productListElement.append(productElement);
        }
        addDragEvenListenters()
    }

    //To listen the drag and drop events
    function addDragEvenListenters(){
        const draggables = document.querySelectorAll(".draggable")
        const dragListProducts = document.querySelectorAll(".draggable-list")
    
        draggables.forEach(draggable=>{
        draggable.addEventListener("dragstart",dragStart)
        })

        dragListProducts.forEach(product=>{
        product.addEventListener("dragover",dragOver)
        product.addEventListener("drop",dragDrop)
        product.addEventListener("dragenter",dragEnter)
        product.addEventListener("dragleave",dragLeave)
        })
    }

    function dragStart(){
    dragStartIndex = +this.closest(".draggable-list").getAttribute("data-index")
    }

    function dragOver(e){
    e.preventDefault()
    }

    function dragEnter(){
    this.classList.add("bg-primary")
    }

    function dragLeave(e){
    if (!this.contains(e.relatedTarget)) {
        this.classList.remove("bg-primary")
    }
    }

    function dragDrop(){
    this.classList.remove("bg-primary")
    const dragEndIndex = +this.getAttribute("data-index")
    swapItems(dragStartIndex, dragEndIndex)
    }

    // To swap the product data after drag and drop
    function swapItems(fromIndex, toIndex){
    const temp = products[fromIndex]
    products[fromIndex] = products[toIndex]
    products[toIndex] = temp

    productListElement.replaceChildren()
    createList()
    saveChangesForm.classList.remove("hide")
    }

    // To save changes to the backend
    function saveChanges(e){
    e.preventDefault();
    productsInput.value = JSON.stringify(products)
    saveChangesForm.submit()
    }

    // To make the add new product form visible
    function addProduct(){
    let productCount = 0
    const sortOrderSelect = document.getElementById("sort-order-select")
    addProductForm.classList.remove("hide")

    for(let product in products){
    const option = document.createElement("option")
    option.value = Number(product)+1
    option.innerHTML = Number(product)+1
    sortOrderSelect.appendChild(option)
    productCount++
    }
    const option = document.createElement("option")
    option.value = productCount+1
    option.innerHTML = productCount+1
    option.selected = true
    sortOrderSelect.appendChild(option)
    }

    // To add new product to the products list
    function addNewProduct(e){
    e.preventDefault()
    const formData = new FormData(e.target)
    const productData = Object.fromEntries(formData.entries())
    const newProduct = {};
    newProduct["title"] = productData["title"]
    newProduct["price"] = productData["price"]
    newProduct["color"] = productData["color"]
    newProduct["subject"] = productData["subject"]
    if(products[Number(productData["sort-order"])-1]){
    products[Object.keys(products).length+1] = products[Number(productData["sort-order"])-1]
    }
    products[Number(productData["sort-order"])-1] = newProduct

    productListElement.replaceChildren()
    createList()
    addProductForm.classList.add("hide")
    saveChangesForm.classList.remove("hide")
    }
    </script>
</body>
</html>
