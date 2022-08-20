<div class="row">
    <div class="col-12 bg-light p-5 rounded">
        <div class="row">
            <div class="col-10">
                <h1>Detail Tiket</h1>
            </div>
        </div>
        <div class="row">
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
            <div class="col-12">
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">
                        <label>Tiket:</label> 
                        <b>
                            {{ $ticket->no_ticket }}
                        </b>
                            <?php
                                if($ticket->type_ticket==1){
                                    echo "(Troubleshooting)";
                                }
                                else if($ticket->type_ticket==2){
                                    echo "(Pengadaan Barang)";
                                }
                                else{
                                    echo "(Maintenance)";
                                }
                            ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">
                            <label>Tanggal:</label> {{ $ticket->createdAt }} <br>
                            <label>Judul:</label> {{ $ticket->judul }} <br>
                            <label>Deskripsi:</label> {{ $ticket->deskripsi }} <br>
                            <label>Foto:</label> 
                            <?php
                                if( file_exists(public_path()."/".$ticket->foto) ){
                            ?>
                                <a href="<?php echo asset($ticket->foto) ?>" target="_blank">{{ $ticket->foto }} </a>
                            <?php
                                }
                                else{
                            ?>
                                {{ $ticket->foto }} 
                            <?php
                                }
                            ?>
                            <br>
                            <label>Status:</label>
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
                            <br>
                            <label>Label:</label> 
                            <?php
                                if($ticket->label == 0){
                            ?>
                                <span class="badge bg-info">Tidak Cepat</span>
                            <?php
                                }
                                else if($ticket->label == 1){
                            ?>
                                <span class="badge bg-success">Biasa</span>
                            <?php
                                }
                                else{
                            ?>
                                <span class="badge bg-danger">Butuh Secepatnya</span>
                            <?php
                                }
                            ?>
                            <br>
                            <label>PIC:</label>
                            <?php
                                if($ticket->pic == null){
                            ?>
                                Belum ditunjuk oleh admin
                            <?php
                                }
                                else{
                            ?>
                                {{ $ticket->pic->nama }}
                            <?php
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card text-dark bg-light mb-3">
                    <div class="card-body">
                        <form action="{{ route('ticket.chat.create',['id'=>$ticket->id]) }}" method="post">
                            @csrf
                            <label>Pesan</label>
                            <input type="text" name="pesan" class="form-control" @if($ticket->id_user_pic == null || $ticket->status==2) {{ "disabled" }} @endif>
                            <input type="submit" value="Kirim" class="btn btn-primary" @if($ticket->id_user_pic == null || $ticket->status==2) {{ "disabled" }} @endif>
                        </form>
                    </div>
                    <div class="card-header">
                        <label>Chat</label></b>
                    </div>
                    <div class="card-body">
                        @php
                            $id_user = 1;
                        @endphp

                        @if (count($chats)==0)
                            Upss belum ada percakapan dengan PIC
                        @else
                            @foreach ($chats as $chat)
                                <div class="<?php echo ($chat->to_name->id==$id_user? "text-start":"text-end"); ?>">
                                    <div class="card-header">
                                        <label>{{ $chat->to_name->nama }}</label></b>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $chat->deskripsi }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif                        
                    </div>
                </div>
                <div class="card text-dark bg-light mb-3">
                    <div class="card-header">
                        <label>Pelacakan</label></b>
                    </div>
                    <div class="card-body">
                        <ol>
                            @if (count($ticket->progress)==0)
                                <span>Upss belum diproses oleh PIC</span>
                            @else
                                @foreach ($ticket->progress as $progress)
                                <li>
                                    <div class="card-header">
                                        <label>{{ $progress->deskripsi }}</label></b>
                                    </div>
                                </li>
                                @endforeach
                            @endif                            
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>