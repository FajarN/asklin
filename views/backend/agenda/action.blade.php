<button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
    @can('agenda-edit')
        <a href="{{ route('agenda.edit', $id) }}" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
    @endcan
    <div class="dropdown-divider"></div>
    @can('agenda-delete')
        <a href="javascript:void(0)" onclick="deleteu({{ $id }})" class="dropdown-item has-icon text-danger" ><i class="fas fa-trash-alt"></i>Hapus</a>
    @endcan
</div>