@extends('master')
@section("feedback_form")


<form method="post" action="/Projects_laravel/mnp-backend/request_update" enctype="multipart/form-data">
  <div class="form-row">
    <div class="form-group col-md-4">
      <label for="product_title">Product Title</label>
      <input type="text" name="type" class="form-control" id="product_title" placeholder="Product Title">
    </div>
    <div class="form-group col-md-4">
      <label for="sku_code">SKU Code</label>
      <input type="text" name="message" class="form-control" id="sku_code" placeholder="SKU Code">
    </div>
    <div class="form-group col-md-4">
      <label for="sku_code">SKU Code</label>
      <input type="text" name="status" class="form-control" id="sku_code" placeholder="SKU Code">
    </div>
    <div class="form-group col-md-12">
    <label for="product_image">Product Image</label>
    <input type="file" name="image" class="form-control-file" id="product_image">
  </div>
  

  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" name="id" value="2">
  <button type="submit" class="btn btn-lg btn-success" name="add_product">Update</button>
</form>
@endsection