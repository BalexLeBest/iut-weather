<!-- resources/views/cities/add.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une ville</title>
</head>
<body>
    <h1>Ajouter une ville</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cities.store') }}" method="POST">
        @csrf
        <label for="city">Nom de la ville:</label>
        <input type="text" name="city" id="city" required>
        <button type="submit">Ajouter</button>
    </form>

    <a href="{{ route('cities.index') }}">Retour à la liste des villes</a>
</body>
</html>
