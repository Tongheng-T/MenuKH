<?php
/**
 * ==========================================================
 * MenuKH
 * Categories
 * ----------------------------------------------------------
 * File : owner/categories.php
 * Version : 1.0.0
 * ==========================================================
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/json.php';
require_once __DIR__ . '/../includes/restaurant.php';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

if (!isLoggedIn()) {

    redirect('../login.php');

}

if ($_SESSION['user']['role'] !== 'owner') {

    redirect('../login.php');

}

/*
|--------------------------------------------------------------------------
| Page Title
|--------------------------------------------------------------------------
*/

$pageTitle = 'Categories';

/*
|--------------------------------------------------------------------------
| Category File
|--------------------------------------------------------------------------
*/

$categoryFile = restaurantFolder() . '/categories.json';

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$keyword = trim($_GET['search'] ?? '');

/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
*/

$categories = JsonDB::orderBy(
    $categoryFile,
    'sort'
);

/*
|--------------------------------------------------------------------------
| Search Filter
|--------------------------------------------------------------------------
*/

if ($keyword !== '') {

    $categories = array_filter(

        $categories,

        function ($category) use ($keyword) {

            return stripos(

                $category['name'] ?? '',

                $keyword

            ) !== false;

        }

    );

}

/*
|--------------------------------------------------------------------------
| Total
|--------------------------------------------------------------------------
*/

$totalCategories = count($categories);

/*
|--------------------------------------------------------------------------
| Empty State
|--------------------------------------------------------------------------
*/

$hasCategories = $totalCategories > 0;

/*
|--------------------------------------------------------------------------
| Load Layout
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../layouts/dashboard/header.php';
?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Categories

        </h2>

        <p class="text-secondary mb-0">

            Manage your restaurant categories.

        </p>

    </div>

    <a

        href="category_create.php"

        class="btn btn-primary">

        <i class="bi bi-plus-lg"></i>

        Add Category

    </a>

</div>

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<form
method="GET"
class="row g-3 mb-4">

<div class="col-md-4">

<input

type="text"

name="search"

class="form-control"

placeholder="Search category..."

value="<?= e($keyword) ?>">

</div>

<div class="col-auto">

<button
class="btn btn-outline-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</form>
<?php if ($hasCategories): ?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <strong>

            Total Categories:

        </strong>

        <?= $totalCategories ?>

    </div>

</div>

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th width="70">

#

</th>

<th>

Category

</th>

<th width="150">

Status

</th>

<th width="100">

Sort

</th>

<th width="180" class="text-end">

Action

</th>

</tr>

</thead>

<tbody>

<?php foreach ($categories as $index => $category): ?>

<tr>

<td>

<?= $index + 1 ?>

</td>

<td>

<div class="d-flex align-items-center gap-3">

<?php if (!empty($category['image'])): ?>

<img
src="<?= e($category['image']) ?>"
width="48"
height="48"
class="rounded object-fit-cover">

<?php else: ?>

<div
class="bg-primary text-white rounded d-flex align-items-center justify-content-center"
style="width:48px;height:48px;">

<i class="bi bi-folder2-open"></i>

</div>

<?php endif; ?>

<div>

<div class="fw-semibold">

<?= e($category['name']) ?>

</div>

<?php if (!empty($category['description'])): ?>

<small class="text-secondary">

<?= e($category['description']) ?>

</small>

<?php endif; ?>

</div>

</div>

</td>

<td>

<?php if (($category['status'] ?? '') === 'active'): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-secondary">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<?= (int)($category['sort'] ?? 0) ?>

</td>

<td class="text-end">

<a
href="category_edit.php?id=<?= urlencode($category['id']) ?>"
class="btn btn-sm btn-outline-primary">

<i class="bi bi-pencil"></i>

</a>

<a
href="category_delete.php?id=<?= urlencode($category['id']) ?>"
class="btn btn-sm btn-outline-danger btn-delete">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="text-center py-5">

<div
class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4"
style="width:90px;height:90px;">

<i class="bi bi-folder2-open fs-1 text-primary"></i>

</div>

<h3 class="fw-bold">

No Categories Yet

</h3>

<p class="text-secondary">

Create your first category to organize your menu.

</p>

<a
href="category_create.php"
class="btn btn-primary">

<i class="bi bi-plus-lg"></i>

Create First Category

</a>

</div>

<?php endif; ?>

</div>

</div>

<script>
/**
 * ==========================================================
 * MenuKH Categories
 * ----------------------------------------------------------
 * Version : 1.0.0
 * ==========================================================
 */

'use strict';

/*
|--------------------------------------------------------------------------
| Auto Focus Search
|--------------------------------------------------------------------------
*/

const searchInput = document.querySelector(
    'input[name="search"]'
);

if (searchInput && searchInput.value !== '') {

    searchInput.focus();

    searchInput.setSelectionRange(
        searchInput.value.length,
        searchInput.value.length
    );

}

/*
|--------------------------------------------------------------------------
| Confirm Delete
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.btn-delete').forEach(button => {

    button.addEventListener('click', function (event) {

        if (!confirm(
            'Are you sure you want to delete this category?'
        )) {

            event.preventDefault();

        }

    });

});

/*
|--------------------------------------------------------------------------
| Live Search (Client Side)
|--------------------------------------------------------------------------
*/

if (searchInput) {

    searchInput.addEventListener('keyup', function () {

        const keyword = this.value.toLowerCase();

        document.querySelectorAll('tbody tr').forEach(row => {

            row.style.display = row.innerText
                .toLowerCase()
                .includes(keyword)
                ? ''
                : 'none';

        });

    });

}

/*
|--------------------------------------------------------------------------
| Highlight Active Menu
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.mk-sidebar a').forEach(link => {

    link.classList.remove('active');

    if (link.getAttribute('href') === 'categories.php') {

        link.classList.add('active');

    }

});
</script>

<?php
require_once __DIR__ . '/../layouts/dashboard/footer.php';