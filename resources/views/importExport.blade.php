<html lang="en">
<head>
<title>Import - Export Laravel 5</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" >
</head>
<body>
<nav class="navbar navbar-default">
<div class="container-fluid">
<div class="navbar-header">
<a class="navbar-brand" href="#">Import - Export in Excel and CSV Laravel 5</a>
</div>
</div>
</nav>
<div class="container">
<div class="alert alert-info">
  <strong>{{ $message }}</strong> 
</div>
<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('/') }}" class="form-inline" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="form-group">
<input type="file" name="import_file" />
</div>
<button class="btn btn-primary">Import File</button>
</form>


<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('/getxls') }}" class="form-inline" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="form-group">
  <label for="sel1">Table:</label>
  <select class="form-control" id="sel1" name="tablename">
  <option selected>None</option>
  @foreach($tables as $table)
  <option value="{{ $table }} ">{{ $table }} </option>
@endforeach
  </select>
</div>
<button class="btn btn-primary">Get Xls File</button>
</form>
</div>

</body>
</html>