@extends('master')
@section("feedback_form")


<form method="post" action="/Projects_laravel/mnp-backend/add-product" enctype="multipart/form-data">
  <div class="form-row">
    <div class="form-group col-md-4">
      <label for="product_title">Product Title</label>
      <select name="groups[]" class="custom-select" multiple>
        <option selected>Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>
    </div>
  </div>

  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <button type="submit" class="btn btn-lg btn-success" name="add_product">Add Product</button>
</form>
@endsection