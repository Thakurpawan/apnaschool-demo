@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Basic Table</h2>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productModal">
    Add Product
  </button>           
  <table class="table">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Color</th>
        <th>Category</th>
        <th>Sub-Category</th>
        <th>Image</th>
        <th>Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($productess as $products)
      <tr>
        <td>{{$products->pname}}</td>
        <td>{{$products->price}}</td>
        <td>{{$products->color}}</td>
        <td>{{$products->category}}</td>
        <td>{{$products->subcategory}}</td>
        <td>{{$products->imgae}}</td>
        <td>{{$products->description}}</td>
        <td>
          <a class="edit" data-id="{{ $products->id }}" title="Edit" data-toggle="tooltip">
            <i class="fa-regular fa-pen-to-square"></i>
          </a>
          <a class="delete" title="Delete" data-toggle="tooltip">
            <i class="fa-solid fa-trash"></i>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="productForm" enctype="multipart/form-data" method="POST">
          @csrf
          <div class="form-group">
            <label for="pname">Product Name:</label>
            <input type="text" class="form-control" id="pname" name="pname" required>
          </div>
          <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" required>
          </div>
          <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control" id="image" name="image">
          </div>
          <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="category" name="category" required>
          </div>
          <div class="form-group">
            <label for="subcategory">Subcategory:</label>
            <input type="text" class="form-control" id="subcategory" name="subcategory">
          </div>
          <div class="form-group">
            <label for="colors">Colors:</label>
            <input type="text" class="form-control" id="colors" name="colors">
          </div>
          <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
      </div>
      <div class="modal-body">
        <form id="editProductForm" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" id="productId">
          <div class="form-group">
            <label for="editPname">Product Name:</label>
            <input type="text" id="editPname" name="pname" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="editPrice">Price:</label>
            <input type="number" id="editPrice" name="price" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="editCategory">Category:</label>
            <input type="text" id="editCategory" name="category" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="editSubcategory">Subcategory:</label>
            <input type="text" id="editSubcategory" name="subcategory" class="form-control">
          </div>
          <div class="form-group">
            <label for="editColors">Colors:</label>
            <input type="text" id="editColors" name="colors" class="form-control">
          </div>
          <div class="form-group">
            <label for="editDescription">Description:</label>
            <textarea id="editDescription" name="description" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label for="editImage">Image:</label>
            <input type="file" id="editImage" name="image" class="form-control">
          </div>
          <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
        <div id="success-message" style="display:none; color: green;">Product updated successfully!</div>
        <div id="error-message" style="display:none; color: red;"></div>
      </div>
    </div>
  </div>
</div>


<div id="responseMessage"></div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
$(document).ready(function() {
    
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route('product.store') }}', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#responseMessage').text(response.success);
                $('#productModal').modal('hide'); 
                $('#productForm')[0].reset(); 
                location.reload(); 
            },
            error: function(response) {
                $('#responseMessage').text('An error occurred.');
            }
        });
    });

    $('.edit').on('click', function() {
        var productId = $(this).data('id');
        
        $.ajax({
            url: '/product/' + productId + '/edit',
            method: 'GET',
            success: function(data) {
                $('#productId').val(data.product.id);
                $('#editPname').val(data.product.pname);
                $('#editPrice').val(data.product.price);
                $('#editCategory').val(data.product.category);
                $('#editSubcategory').val(data.product.subcategory);
                $('#editColors').val(data.product.color);
                $('#editDescription').val(data.product.description);
                
                $('#editProductModal').modal('show');
            },
            error: function(response) {
                console.log(response);
            }
        });
    });

 
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var productId = $('#productId').val();

        $.ajax({
            url: '/products/' + productId,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#success-message').text('Product updated successfully!').show();
                $('#error-message').hide();
                $('#editProductModal').modal('hide');
                location.reload(); 
            },
            error: function(xhr) {
                $('#error-message').text(xhr.responseText).show();
                $('#success-message').hide();
            }
        });
    });
});
</script>
@endsection
