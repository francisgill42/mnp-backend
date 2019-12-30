@extends('master')
@section("feedback_form")

<div class="row">
    <div class="col-md-12 mt-5">
    
    <?php
    if($errors->any()){
      foreach ($errors->all() as $error)
      {
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
      }
    }
    
    if(session('msg')){
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo session('msg'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
    ?>
    </div>
</div>
<form method="post" action="/Projects_laravel/mnp-backend/add-product" enctype="multipart/form-data">
  <div class="form-row">
    <div class="form-group col-md-4">
      <label for="product_title">Product Title</label>
      <input type="text" name="product_title" class="form-control" id="product_title" placeholder="Product Title">
    </div>
    <div class="form-group col-md-4">
      <label for="sku_code">SKU Code</label>
      <input type="text" name="sku_code" class="form-control" id="sku_code" placeholder="SKU Code">
    </div>
  <div class="form-group col-md-4">
    <label for="product_quantity">Product Quantity</label>
    <input type="number" name="product_quantity" class="form-control" id="product_quantity" placeholder="Product Quantity">
  </div>
  <div class="form-group col-md-6">
    <label for="product_price">Product Price</label>
    <input type="text" name="product_price" class="form-control" id="product_price" placeholder="Product Price">
  </div>
  <div class="form-group col-md-6">
    <label for="expiry_date">Expiry Date</label>
    <input type="date" name="expiry_date" class="form-control" id="expiry_date" placeholder="Expiry Date">
  </div>
    <div class="form-group col-md-4">
      <label for="unit_of_measurement">Unit of Measurement</label>
      <input type="text" name="unit_of_measurement" class="form-control" id="unit_of_measurement" placeholder="Unit of Measurement">
    </div>
  <div class="form-group col-md-4">
      <label for="unit_in_case">Unit in Case</label>
      <input type="text" name="unit_in_case" class="form-control" id="unit_in_case" placeholder="Unit in Case">
    </div>  
  <div class="form-group col-md-4">
    <label for="weight">Weight</label>
    <input type="text" name="weight" class="form-control" id="weight" placeholder="Weight">
  </div>
  <div class="form-group col-md-12">
    <label for="description">Description</label>
    <textarea name="description" class="form-control" id="description" rows="4" placeholder="Description here....."></textarea>
  </div>
  <div class="form-group col-md-12">
    <label for="product_image">Product Image</label>
    <input type="file" name="file" class="form-control-file" id="product_image">
  </div>
  <div class="form-group col-md-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="status" name="status" value="1">
      <label class="form-check-label" for="status">
        Is Active
      </label>
    </div>
  </div>

  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <button type="submit" class="btn btn-lg btn-success btn-block" name="add_product">Add Product</button>
</form>
@endsection