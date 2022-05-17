@extends('template.layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <h2 class="mb-4">UPS</h2>

                            
                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                {{Session::get('success')}}
                                </div>
                            @endif

                           

                            <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#importPdf">
                                Upload PDF
                            </button>

                            <br><br>


                            @csrf
                                <table class="table table-bordered datatables">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Dokumen</th>
                                            <th>Tanggal Upload</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                     
                            <!-- Import Excel -->
                            <div class="modal fade" id="importPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="POST" action="{{ route('ag_ups.index') }}" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Upload UPS</h5>
                                            </div>
                                            <div class="modal-body">
                     
                                                {{ csrf_field() }}

                                                @if(Session::has('error'))
                                                <div class="alert alert-danger">
                                                {{Session::get('error')}}
                                                </div>
                                            @endif
                     
                                                <label>Pilih file pdf</label>
                                                {{-- <div class="form-group">
                                                    <input type="file" name="file" required="required">
                                                </div> --}}
                                                <form method="POST" action="{{ route('ag_ups.index') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <input type="file" name="datafile" required>
                                                        </div>
                                                    </div>
                                                    <p class="fw-normal fs-6">Acceptable file types are PDF.
                                                    </p>
                                                    <p class="fw-normal fs-6">Maks file size 10MB.</p>
                                            
                                            <div class="form-group">
                                                <label name="document_name" class="col-sm-4 control-label"> Nama Dokumen </label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="document_name" name="document_name" placeholder="Masukkan nama dokumen..." maxlength="50" >
                                                </div>
                                            </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                     
                            

    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        var table = $('.datatables').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: "{{ route('ag_ups.index') }}",
                method: 'GET',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', width: '5%'},
                    {data: 'document_name', name: 'document_name', width: '15%'},
                    {data: 'created_at', name: 'created_at', width: '15%'},
                ],
                order: [
                    [0, 'desc'],
                ],
            });
        });
    </script>

@endsection