Le chapitre n'existe pas.
Voici la liste des chapitres du livre {{$book['name']}} :
<ul>
@foreach ($chapters as $chapter)
    <li>CH {{ $chapter->order }} : {{ $chapter->title }}</li>
@endforeach
</ul>