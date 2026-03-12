 <!DOCTYPE html>
 <html lang="ru">
 <head>
     <meta charset="UTF-8">
     <title>Редактирование записи</title>
     <!-- Подключение Bootstrap 5 и других стилей -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"  crossorigin="anonymous"></script>
 
     <!-- Add Quill's stylesheet -->
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css">
 
     <!-- Add Quill's JavaScript -->
     <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
 
     <style>
         #quill-editor {
             height: 150px; /* 5 строк * 20 пикселей на строку */
         }
     </style>
 
 </head>
 <nav class="navbar navbar-expand-md bg-dark navbar-dark">
     <a class="navbar-brand" href="#">Редактировать новость</a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="collapsibleNavbar">
         <ul class="navbar-nav">
             <li class="nav-item">
                 <a class="nav-link" href="../admin/admin.php">Админ-панель</a>
             </li>
         </ul>
     </div>
 </nav>
 <body>
 <div class="container">
-    <form id="edit-form" enctype="multipart/form-data">
-        <input type="hidden" id="edit-id" name="id">
-        <div class="mb-3">
-            <label for="edit-title" class="form-label">Заголовок</label>
-            <input type="text" class="form-control" id="edit-title" name="title">
-        </div>
+    <form id="edit-form" enctype="multipart/form-data">
+        <input type="hidden" id="edit-id" name="id" value="<?php echo (int)$row['id']; ?>">
+        <div class="mb-3">
+            <label for="edit-title" class="form-label">Заголовок</label>
+            <input type="text" class="form-control" id="edit-title" name="title" value="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>">
+        </div>
         <div class="mb-3">
             <label for="description" class="form-label">Описание</label>
             <div id="quill-editor"></div>
             <input type="hidden" id="quill-data" name="description">
         </div>
 
         <div class="mb-3">
-            <label for="edit-thumbnail" class="form-label">Миниатюра</label>
-            <input type="file" class="form-control" id="edit-image" name="image" accept="image/jpeg, image/png, image/gif">
-            <img src="" alt="Миниатюра" id="edit-image-preview" width="200" style="display: none;">
-        </div>
-
-        <div class="mb-3">
-            <label for="edit-category" class="form-label">Категория</label>
-            <select name="category" id="edit-category" class="form-control">
-                <option value="garden">Сад / Огород</option>
-                <option value="weight">Похудение</option>
-                <option value="pressure">Давление</option>
-                <option value="joints">Суставы</option>
-                <option value="diabetes">Диабет</option>
-                <option value="beauty">Красота</option>
-                <option value="general">Общее</option>
-            </select>
-        </div>
+            <label for="edit-thumbnail" class="form-label">Миниатюра</label>
+            <input type="file" class="form-control" id="edit-image" name="image" accept="image/jpeg, image/png, image/gif">
+            <img src="<?php echo !empty($row['image']) ? '../images/' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') : ''; ?>" alt="Миниатюра" id="edit-image-preview" width="200" style="display: <?php echo !empty($row['image']) ? 'block' : 'none'; ?>;">
+        </div>
+
+        <div class="mb-3">
+            <label for="edit-category" class="form-label">Категория</label>
+            <select name="category" id="edit-category" class="form-control">
+                <option value="garden" <?php if ($row['category'] === 'garden') echo 'selected'; ?>>Сад / Огород</option>
+                <option value="weight" <?php if ($row['category'] === 'weight') echo 'selected'; ?>>Похудение</option>
+                <option value="pressure" <?php if ($row['category'] === 'pressure') echo 'selected'; ?>>Давление</option>
+                <option value="joints" <?php if ($row['category'] === 'joints') echo 'selected'; ?>>Суставы</option>
+                <option value="diabetes" <?php if ($row['category'] === 'diabetes') echo 'selected'; ?>>Диабет</option>
+                <option value="beauty" <?php if ($row['category'] === 'beauty') echo 'selected'; ?>>Красота</option>
+                <option value="general" <?php if ($row['category'] === 'general') echo 'selected'; ?>>Общее</option>
+            </select>
+        </div>
 
         <button type="submit" class="btn btn-primary">Сохранить изменения</button>
         <a href="../admin/admin.php" class="btn btn-secondary">Отмена</a>
     </form>
 </div>
 
 <script type="text/javascript">
     function getUrlParameter(name) {
         name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
         const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
         const results = regex.exec(location.search);
         return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
     }
 
     document.addEventListener('DOMContentLoaded', () => {
         const quill = new Quill('#quill-editor', {
             modules: {
                 toolbar: true
             },
             theme: 'snow'
         });
 
-        const id = getUrlParameter('id');
-        loadData(id, quill);
+        const initialDescription = <?php echo json_encode($row['description']); ?>;
+        quill.setContents(quill.clipboard.convert(initialDescription || ''));
 
         document.getElementById('edit-form').addEventListener('submit', (event) => {
             event.preventDefault();
             const quillData = quill.root.innerHTML;
             document.getElementById('quill-data').value = quillData;
 
             const formData = new FormData(event.target);
             fetch('update_data.php', {
                 method: 'POST',
                 body: formData
             }).then(response => response.json())
               .then(data => {
                   if (data.status === 'success') {
                       window.location.href = '../admin/admin.php';
                   } else {
                       alert(data.message);
                   }
               });
         });
     });
 
-    async function loadData(id, quill) {
-        const response = await fetch('get_data.php?id=' + id);
-        if (response.ok) {
-            const data = await response.json();
-            document.getElementById('edit-id').value = data.id;
-            document.getElementById('edit-title').value = data.title;
-            quill.setContents(quill.clipboard.convert(data.description));
-            document.getElementById('edit-category').value = data.category;
-            document.getElementById('edit-image-preview').src = '../images/' + data.image;
-            document.getElementById('edit-image-preview').style.display = 'block';
-        } else {
-            alert('Ошибка при загрузке данных');
-        }
-    }
-</script>
+</script>
 
 </body>
-</html>
\ No newline at end of file
+</html>
