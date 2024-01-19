<tr>
    <td rowspan="2">{{ $user->id }}</td>
    <th scope="row">
        {{ $user->name }} {{ $user->status }}
        @if($user->role != 'user')
            ({{ $user->role }})
        @endif
        <span class="status st-{{ $user->state }}"></span>
        <span class="note">{{ $user->team->name }}</span>
    </th>
    <td>{{ $user->email }}</td>
    <td>
        <span class="note">{{ $user->created_at->format('d/m/Y') }}</span>
    </td>
    <td>
        <span class="note">{{ optional($user->last_login_at)->format('d/m/Y h:ia') ?: 'N/A' }}</span>
    </td>
    <td class="text-right">
        @if ($user->trashed())
            <form action="{{ route('user.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link"><span class="material-symbols-outlined">delete_forever</span>
                </button>
            </form>
        @else
            <form action="{{ route('user.trash', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <a href="{{ route('users.show', $user) }}"
                   class="btn btn-outline-secondary btn-sm"><span
                            class="material-symbols-outlined">visibility</span></a>
                <a href="{{ route('user.edit', $user) }}"
                   class="btn btn-outline-secondary btn-sm"><span
                            class="material-symbols-outlined">edit</span></a>
                <button type="submit" class="btn btn-outline-danger btn-sm"><span
                            class="material-symbols-outlined">delete</span></button>
            </form>
        @endif
    </td>
</tr>
<tr class="skills">
    <td colspan="1"><span class="note">{{ $user->profile->profession->title }}</span></td>
    <td colspan="4"><span
                class="note">{{ $user->skills->implode('name', ', ') ?: 'Sin habilidades' }}</span></td>
</tr>
