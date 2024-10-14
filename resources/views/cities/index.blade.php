<!-- resources/views/cities/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos villes</title>
</head>
<body>
    <h1>Vos villes</h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    <ul>
        @foreach ($userCities as $city)
            <li>
                {{ $city->city }}

                @if ($city->favorite)
                    <strong>(Ville favorite)</strong>
                @endif

                <form action="{{ route('cities.destroy', $city->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>

                <form action="{{ route('cities.favorite', $city->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Définir comme favorite</button>
                </form>

                <form action="{{ route('cities.send-forecast', $city->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Envoyer les prévisions par email</button>
                </form>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('cities.add') }}">Ajouter une nouvelle ville</a>
</body>
</html>