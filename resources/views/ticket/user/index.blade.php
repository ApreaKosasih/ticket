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