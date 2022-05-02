<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

</head>
<body>


<div class="content">
    <h1 style="text-align: center">Content parser</h1>

    <form method="POST" action="{{route('parse.content')}}">
        <button type="submit" class="btn btn-secondary parse mb-4">Parse Folder</button>
    </form>

    @if(empty($databases))
        <h1>Database list is empty</h1>
    @else
        <button type="button" class="btn btn-primary select-all">Select all</button>
        <table class="table table-bordered" style="margin: auto">
            <thead>
            <tr>
                <th scope="col">Db name</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($databases as $key => $dbName)
                <tr>
                    <td>
                        <input class="checkbox" type="checkbox" data-db={{$dbName}}>
                        <span class="ml-10">{{$dbName}}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger delete" data-db={{$dbName}}>X</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button type="button" class="btn btn-success download">Get .txt</button>

        <div class="links">
            @foreach($databases as $database)
                <a href="{{route('get.content', ['db' => $database])}}">{{route('get.content', ['db' => $database])}}</a>
            @endforeach
        </div>
    @endif
</div>
</body>

<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
        crossorigin="anonymous"></script>
{{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>--}}


<script  src="{{ asset('js/script.js') }}" ></script>
<script>

    $('.delete').click(function () {
        let tmp = [];
        tmp.push($(this).attr("data-db"));
        $('.checkbox').each(function () {
            if ($(this).is(":checked")) {
                tmp.push($(this).attr("data-db"));
            }
        });
        var dbs = tmp.filter((v, i, a) => a.indexOf(v) === i);
        if (dbs) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('db.drop') }}",
                data: {dbs: dbs},
                success: function (data) {
                    if(data){
                        location.reload();
                    }
                }
            });

        }
    })

    $('.select-all').click(function () {
        $('.checkbox').each(function () {
            $(this).prop('checked', true);
        });
    })

    $('.download').click(function () {
        let tmp = [];
        $('.checkbox').each(function () {
            if ($(this).is(":checked")) {
                tmp.push($(this).attr("data-db"));
            }
        });
        if (tmp) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('download.content') }}",
                data: {dbs: tmp},
                success: function (data) {
                    console.log(data);
                    downloadTxt(data);
                }
            });
        }
    })
</script>

</html>

<style>
    a{
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .links{
        display: grid;
    }

    .content {
        width: 100%;
        display: grid;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
</style>
