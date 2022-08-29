<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script><script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container{
        width: 100% !important;
    }
</style>
@if( Session::has('type_modal') && Session::has('message') )
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header <?php echo (Session::get('type_modal')=="success" ? "bg-success":"bg-danger")?>">
                <strong class="me-auto text-white">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ Session::get('message') }}
            </div>
        </div>
    </div>
    @php
        Session::forget('type_modal');
        Session::forget('message');
    @endphp
@endif

<div class="row">
    <div class="col-12 bg-light p-5 rounded">
        <div class="row">
            <div class="col-10">
                <h1>Tiket</h1>
                <h5>(Tugas)</h5>
            </div>
            <div class="col-2">
                @if ($tickets->total_waiting<=3)
                    <a class="btn btn-lg btn-primary pull-right" href="{{ route('ticket.add') }}" role="button">Tambah Tiket</a>
                @else
                    <button class="btn btn-lg btn-secondary pull-right" role="button">Tambah Tiket</button>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Tanggal</td>
                            <td>Nomor Tiket</td>
                            <td>Judul</td>
                            <td>Deskripsi</td>
                            <td>Foto</td>
                            <td>PIC</td>
                            <td>Status</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets->tugas as $index => $ticket)
                        <tr>
                            <td><?php echo $index+1; ?></td>
                            <td><?php echo \Carbon\Carbon::parse($ticket->created_at)->format("l, j F Y"); ?></td>
                            <td>
                                <?php echo $ticket->no_ticket; ?>
                                <br>
                                <?php
                                    if($ticket->label == 0){
                                ?>
                                    <span class="badge bg-secondary">Tidak Butuh Cepat</span>
                                <?php
                                    }
                                    else if($ticket->label == 1){
                                ?>
                                    <span class="badge bg-success">Biasa</span>
                                <?php
                                    }
                                    else{
                                ?>
                                    <span class="badge bg-danger">Butuh Cepat</span>
                                <?php
                                    }
                                ?>
                            </td>
                            <td><?php echo $ticket->judul; ?></td>
                            <td><?php echo $ticket->deskripsi; ?></td>
                            <td>
                                <?php
                                    if($ticket->foto != null){
                                        if( file_exists(public_path()."/ticket/$ticket->foto") ){
                                    ?>
                                            <a href="<?php echo asset("/ticket/$ticket->foto") ?>" target="_blank">{{ $ticket->foto }} </a>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        {{ $ticket->foto }} 
                                    <?php
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                            @if (count($ticket->userPic)==0)
                                    <button type="button" class="btn btn-primary AddMember" data-url="{{ route('ticket.pic_member.create',['id'=>$ticket->id]) }}" data-bs-toggle="modal" data-bs-target=".modalPIC">Tambah PIC</button>
                                @else
                                    {{ $ticket->userPic[0]->nama_karyawan }}
                                @endif
                            </td>
                            <td>
                                <?php
                                    if($ticket->status == 0){
                                ?>
                                    <span class="badge bg-warning">Menunggu Sesi</span>
                                <?php
                                    }
                                    else if($ticket->status == 1){
                                ?>
                                    <span class="badge bg-success">Memulai Sesi</span>
                                <?php
                                    }
                                    else{
                                ?>
                                    <span class="badge bg-danger">Menutup Sesi</span>
                                <?php
                                    }
                                ?>
                            </td>
                            <td>
                                @if (
                                    ($ticket->user[0]->divisi->id==4 && in_array($ticket->user[0]->level->id, [1,2,3,4])) &&
                                    ($ticket->userPic[0]->divisi->id==4 && in_array($ticket->userPic[0]->level->id, [1,2,3,4])) &&
                                    $ticket->user[0]->nik != Session::get('id_user')
                                )
                                    <a href="{{ route('ticket.detail', ['id'=> $ticket->id]) }}" class="btn btn-primary">detail</a>
                                    @if ($ticket->status != 2)
                                        <a href="{{ route('ticket.destroy', ['id'=> $ticket->id]) }}" class="btn btn-danger">tutup tiket</a>        
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 bg-light p-5 rounded">
        <div class="row">
            <div class="col-10">
                <h1>Tiket</h1>
                <h5>(Pribadi)</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Tanggal</td>
                            <td>Nomor Tiket</td>
                            <td>Judul</td>
                            <td>Deskripsi</td>
                            <td>Foto</td>
                            <td>PIC</td>
                            <td>Status</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets->pribadi as $index => $ticket)
                        <tr>
                            <td><?php echo $index+1; ?></td>
                            <td><?php echo \Carbon\Carbon::parse($ticket->created_at)->format("l, j F Y"); ?></td>
                            <td>
                                <?php echo $ticket->no_ticket; ?>
                                <br>
                                <?php
                                    if($ticket->label == 0){
                                ?>
                                    <span class="badge bg-secondary">Tidak Butuh Cepat</span>
                                <?php
                                    }
                                    else if($ticket->label == 1){
                                ?>
                                    <span class="badge bg-success">Biasa</span>
                                <?php
                                    }
                                    else{
                                ?>
                                    <span class="badge bg-danger">Butuh Cepat</span>
                                <?php
                                    }
                                ?>
                            </td>
                            <td><?php echo $ticket->judul; ?></td>
                            <td><?php echo $ticket->deskripsi; ?></td>
                            <td>
                                <?php
                                    if($ticket->foto != null){
                                        if( file_exists(public_path()."/ticket/$ticket->foto") ){
                                    ?>
                                            <a href="<?php echo asset("/ticket/$ticket->foto") ?>" target="_blank">{{ $ticket->foto }} </a>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        {{ $ticket->foto }} 
                                    <?php
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo ( count($ticket->userPic)==0? "Belum ada PIC":$ticket->userPic[0]->nama_karyawan); ?>
                            </td>
                            <td>
                                <?php
                                    if($ticket->status == 0){
                                ?>
                                    <span class="badge bg-warning">Menunggu Sesi</span>
                                <?php
                                    }
                                    else if($ticket->status == 1){
                                ?>
                                    <span class="badge bg-success">Memulai Sesi</span>
                                <?php
                                    }
                                    else{
                                ?>
                                    <span class="badge bg-danger">Menutup Sesi</span>
                                <?php
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="{{ route('ticket.detail', ['id'=> $ticket->id]) }}" class="btn btn-primary">detail</a>
                                @if ($ticket->status != 2)
                                    <a href="{{ route('ticket.destroy', ['id'=> $ticket->id]) }}" class="btn btn-danger">tutup tiket</a>        
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modalPIC">
    <form action="" method="post" id="FormPicMember">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah PIC</h5>
            </div>
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <label>PIC</label>
                        <select name="pic" class="form-control MainPIC" required>
                            <option value="">-- Pilih PIC --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->nik }}">{{ $user->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label>PIC Member</label>
                        <select name="pic_member[]" class="form-control MemberPIC" multiple>
                            <option value="">-- Pilih PIC Member --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->nik }}">{{ $user->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('.MainPIC').select2({
        dropdownParent: $('.modalPIC')
    });
    $('.MemberPIC').select2({
        dropdownParent: $('.modalPIC')
    });
    $('.AddMember').on('click', function (e) {
        var url = $(this).attr('data-url');
        $("#FormPicMember").attr("action", url);
        console.log(url)                    
    });
});
</script>