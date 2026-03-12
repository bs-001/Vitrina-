<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Добавить новость</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>

<nav class="navbar navbar-expand-md bg-dark navbar-dark">
<a class="navbar-brand" href="#">Добавить новость</a>

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="collapsibleNavbar">
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" href="admin.php">Админка</a>
</li>
</ul>
</div>
</nav>


<div class="container-fluid">
<div class="row">


<div class="col-md-8">

<h2>Добавить новость</h2>

<form id="addNewsForm" enctype="multipart/form-data">

<div class="form-group">
<label>Заголовок</label>
<input type="text" class="form-control" name="title" id="title" required>
</div>

<div class="form-group">
<label>Описание</label>
<textarea class="form-control" name="description" id="description" rows="6" required></textarea>
</div>


<div class="form-group">
<label>Категория</label>
<select class="form-control" name="category" id="category">

<option value="garden">Сад / Огород</option>

<option value="weight">Похудение</option>

<option value="pressure">Давление</option>

<option value="joints">Суставы</option>

<option value="diabetes">Диабет</option>

<option value="beauty">Красота</option>

<option value="general">Общее</option>

</select>
</div>


<div class="form-group">
<label>Изображение</label>
<input type="file" class="form-control-file" name="image" id="image">
</div>


<button type="submit" class="btn btn-primary">
Добавить новость
</button>

</form>

</div>


<div class="col-md-4">

<h3>Категории</h3>

<ul class="list-group">

<li class="list-group-item">Сад / огород</li>
<li class="list-group-item">Похудение</li>
<li class="list-group-item">Давление</li>
<li class="list-group-item">Суставы</li>
<li class="list-group-item">Диабет</li>
<li class="list-group-item">Красота</li>

</ul>

</div>

</div>
</div>


<script>

$("#addNewsForm").submit(function(e){

e.preventDefault();

var formData = new FormData(this);

$.ajax({

url: "../config/insert_data.php",
type: "POST",
data: formData,
processData: false,
contentType: false,

success: function(response){

alert("Новость добавлена");

location.reload();

}

});

});

</script>

</body>
</html>