<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Multiselect Delete Data
## Langkah 1 Buat model dan migration
```
php artisan make:model Article -m
```

Jalankan migrasi untuk membuat tabel:
```
php artisan migrate
```

## Langkah 2 Seeder untuk data dummy
Buat seeder untuk mengisi beberapa data artikel:
```
php artisan make:seeder ArticlesTableSeeder
```
Edit seeder di `database/seeders/ArticlesTableSeeder.php`:
```
use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    public function run()
    {
        Article::factory()->count(50)->create();
    }
}
```

Setelah Data Seeder sudah siap, Berikut adalah langkah-langkah untuk membuat factory dan mengisinya dengan data:
```
php artisan make:factory ArticleFactory --model=Article
```

Edit file `database/factories/ArticleFactory.php` yang baru saja dibuat:
```
<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];
    }
}
```

Setelah `ArticelTableSeeder` dan `ArticleFactory` sudah siap Jalankan seeder:
```
php artisan db:seed --class=ArticlesTableSeeder
```

## Langkah 3: Route dan Controller
Buat controller untuk menangani permintaan show artikel dan delete selected artikel:
```
php artisan make:controller ArticleController
```

Edit controller `app/Http/Controllers/ArticleController.php`:
```
<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');
            Article::whereIn('id', $ids)->delete();

            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showItems()
{
    $items = Article::latest()->get();
    return view('article.index', compact('items'));
}
}
```

Tambahkan route di `routes/web.php`:
```
Route::delete('/delete-selected', [ArticleController::class, 'deleteSelected'])->name('delete.selected');
Route::get('/show-items', [ArticleController::class, 'showItems'])->name('show.items');
```

## Langkah 5 : Tambahkan View dan Javascript
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Multi-Select Delete</title>
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Select Items to Delete</h2>
        <form id="delete-form">
          <button type="button" id="delete-button" class="btn btn-danger mb-3">Delete Selected</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selected_all_ids"></th>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Body</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" class="checkbox_ids" value="{{ $item->id }}">
                        </td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->body }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </form>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
      // function select all
    $(function(e) {
      $("#selected_all_ids").click(function() {
        $('.checkbox_ids').prop('checked', $(this).prop('checked'));
      });
    });

    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#delete-button').on('click', function() {
            var ids = [];
            $('input[name="ids[]"]:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length > 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover these records!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('delete.selected') }}',
                            type: 'DELETE',
                            data: {
                                ids: ids
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your records have been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting records.',
                                    'error'
                                );
                                console.error('Error:', error);
                                console.error('Status:', status);
                                console.error('Response:', xhr.responseText);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    'No selection',
                    'Please select at least one item to delete.',
                    'info'
                );
            }
        });
    </script>
</body>
</html>
```

## Langkah 6 : Test
```
php artisan serve
```

Buka browser dan akses `http://127.0.0.1:8000/show-items`. Anda akan melihat artikel dimuat dan checkbox untuk memilih lebih banyak artikel.

Dengan mengikuti langkah-langkah di atas, Anda akan memiliki fitur multi select artikel yang bekerja dengan Laravel di backend dan Bootstrap 5 di frontend.

