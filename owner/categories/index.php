<?php
/**
 * ==========================================================
 * MenuKH
 * Categories Module
 * ----------------------------------------------------------
 * File : owner/categories/index.php
 * Version : 1.0.0
 * ==========================================================
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/restaurant.php';
require_once __DIR__ . '/../../includes/routes.php';
/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

if (!isLoggedIn()) {
    redirect('../../login.php');
}

if (($_SESSION['user']['role'] ?? '') !== 'owner') {
    redirect('../../login.php');
}

/*
|--------------------------------------------------------------------------
| Page
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
| Statistics
|--------------------------------------------------------------------------
*/

$totalCategories = count($categories);

$activeCategories = count(
    array_filter(
        $categories,
        fn($row) => ($row['status'] ?? '') === 'active'
    )
);

$inactiveCategories =
    $totalCategories - $activeCategories;

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$flash = getFlash();

/*
|--------------------------------------------------------------------------
| Load Layout
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../../layouts/dashboard/header.php';
?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Categories

        </h2>

        <p class="text-secondary mb-0">

            Organize your restaurant menu categories.

        </p>

    </div>

    <a
        href="create.php"
        class="btn btn-primary">

        <i class="bi bi-plus-lg"></i>

        Add Category

    </a>

</div>

<?php if($flash): ?>

<div class="alert alert-<?= e($flash['type']) ?>">

    <?= e($flash['message']) ?>

</div>

<?php endif; ?>

<div class="row mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <small class="text-secondary">

                    Total Categories

                </small>

                <h2 class="fw-bold mb-0">

                    <?= $totalCategories ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <small class="text-secondary">

                    Active

                </small>

                <h2 class="fw-bold text-success mb-0">

                    <?= $activeCategories ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <small class="text-secondary">

                    Inactive

                </small>

                <h2 class="fw-bold text-danger mb-0">

                    <?= $inactiveCategories ?>

                </h2>

            </div>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body">

<form
method="GET"
class="row g-3 mb-4">

<div class="col-md-5">

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

<div class="col-auto">

<a
href="index.php"
class="btn btn-outline-secondary">

Reset

</a>

</div>

</form>
<?php if ($totalCategories > 0): ?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th width="70">

#

</th>

<th>

Category

</th>

<th width="120">

Status

</th>

<th width="100">

Sort

</th>

<th width="170" class="text-end">

Action

</th>

</tr>

</thead>

<tbody>

<?php foreach($categories as $index => $category): ?>

<tr>

<td>

<?= $index + 1 ?>

</td>

<td>

<div class="d-flex align-items-center gap-3">

<?php if(!empty($category['image'])): ?>

<img

src="<?= e($category['image']) ?>"

class="rounded"

style="width:52px;height:52px;object-fit:cover;">

<?php else: ?>

<div
class="rounded bg-primary text-white d-flex justify-content-center align-items-center"
style="width:52px;height:52px;">

<i class="bi bi-folder2-open"></i>

</div>

<?php endif; ?>

<div>

<div class="fw-semibold">

<?= e($category['name']) ?>

</div>

<?php if(!empty($category['description'])): ?>

<small class="text-secondary">

<?= e($category['description']) ?>

</small>

<?php endif; ?>

</div>

</div>

</td>

<td>

<?php if(($category['status'] ?? '') === 'active'): ?>

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

<div class="btn-group">

<a

href="edit.php?id=<?= urlencode($category['id']) ?>"

class="btn btn-outline-primary btn-sm">

<i class="bi bi-pencil-square"></i>

</a>

<a

href="status.php?id=<?= urlencode($category['id']) ?>"

class="btn btn-outline-warning btn-sm">

<i class="bi bi-arrow-repeat"></i>

</a>

<a

href="delete.php?id=<?= urlencode($category['id']) ?>"

class="btn btn-outline-danger btn-sm btn-delete">

<i class="bi bi-trash"></i>

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="text-center py-5">

<div
class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center mb-4"
style="width:100px;height:100px;">

<i class="bi bi-folder2-open fs-1 text-primary"></i>

</div>

<h3 class="fw-bold">

No Categories Found

</h3>

<p class="text-secondary mb-4">

Create your first category to organize your restaurant menu.

</p>

<a

href="create.php"

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
 * MenuKH
 * Categories Module
 * ----------------------------------------------------------
 * File : owner/categories/index.php
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
| Live Search
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
| Auto Hide Flash Message
|--------------------------------------------------------------------------
*/

const flashAlert = document.querySelector('.alert');

if (flashAlert) {

    setTimeout(() => {

        flashAlert.classList.add('fade');

        setTimeout(() => {

            flashAlert.remove();

        }, 300);

    }, 3000);

}
</script>

<?php require_once __DIR__ . '/../../layouts/dashboard/footer.php'; ?>