@if(isset($importStep) && !empty($importStep) && $importStep == '1')
<form class="edit-form">
    <div class="card custom-card profile-details import-page">
        <div class="basic-details">
            <h6 class="card-heading">Import</h6>
        </div>
        <div class="import_browse">
            <div class="browse">
                <p>Upload CSV File</p>
                <div class="file">
                    <label for="fileLoader"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.66537 4.99992C9.66537 5.34992 9.43203 5.58325 9.08203 5.58325H5.58203V9.08325C5.58203 9.43325 5.3487 9.66659 4.9987 9.66659C4.6487 9.66659 4.41536 9.43325 4.41536 9.08325V5.58325H0.915365C0.565365 5.58325 0.332031 5.34992 0.332031 4.99992C0.332031 4.64992 0.565365 4.41659 0.915365 4.41659H4.41536V0.916585C4.41536 0.566585 4.6487 0.333252 4.9987 0.333252C5.3487 0.333252 5.58203 0.566585 5.58203 0.916585V4.41659H9.08203C9.43203 4.41659 9.66537 4.64992 9.66537 4.99992Z" fill="#696868"/>
                        </svg>
                         Browse</label>
                    <input id="fileLoader" type="file" wire:model.defer="xlsxFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    <span id="filename">@if(isset($selectedFile) && !empty($selectedFile)) {{$selectedFile}} @else No file selected @endif</span>
                </div>
            </div>
            @error('xlsxFile')
            <span class="error" style="margin-left: 140px;">{{$message}}</span>
            @enderror
            <br>
            <div class="table-details select-table-custom" style="width:140px; font-size: 14px; font-weight: 400;">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="t-basic"  style="padding: 10px 80px 0px 0px; font-size: 14px; font-weight: 400;">Year</th>
                            <td>
                                <select class="form-select" name="year" wire:model.defer="year" style="width: 200px;">
                                    <option value="">Select Year</option>
                                    @if(isset($getyear) && count($getyear))
                                        @foreach($getyear as $getyeardata)
                                            <option value="{{$getyeardata->id}}">{{$getyeardata->year}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('year')
                                <span class="error">{{$message}}</span>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-btn">
            <button wire:click="importFrist()" type="button" class="btn action-btn">Import</button>
        </div>
    </div>
</form>
@elseif(isset($importStep) && !empty($importStep) && $importStep == '2')
<form class="edit-form">
    <div class="card custom-card profile-details import-page">
        <div class="step-buttons">
            <div class="sb-flex">
                <button type="button" class="stp-line active">Upload CSV File</button>
                <button type="button" class="stp-line active half-line">Column Mapping</button>
                <button type="button" class="stp-line">Import</button>
                <button type="button" class="stp-line">Done</button>
            </div>
        </div>
        <div class="basic-details">
            <h6 class="card-heading">Mapping</h6>
        </div>
        <div class="import-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Column Name</th>
                        <th>Map to Field</th>
                    </tr>
                </thead>

                <tbody>
                    @if(isset($databaseFields) && count($databaseFields))
                        @foreach($databaseFields as $key => $databaseFieldsData)
                            @if($databaseFieldsData!='id' && $databaseFieldsData!='user_id' && $databaseFieldsData!='import_data_info_id' && $databaseFieldsData!='nationality_id' && $databaseFieldsData!='study_country_id' && $databaseFieldsData!='image_bank_id' && $databaseFieldsData!='status' && $databaseFieldsData!='deleted_at' && $databaseFieldsData!='created_at' && $databaseFieldsData!='updated_at' && $databaseFieldsData!='push_notification' && $databaseFieldsData!='language')
                                <tr>
                                    <td>{{str_replace("_"," ",ucwords($databaseFieldsData))}}</td>
                                    <td>
                                        <div class="import_select">
                                            <select class="form-select" wire:model.defer="mappingRow.{{$key}}" data-style="btn-default" tabindex="null">
                                                <option value="">Select Row</option>
                                                @if(isset($headerRow[0][0]) && count($headerRow[0][0]))
                                                    @foreach($headerRow[0][0] as $headerkey => $headerRowData)
                                                        <option value="{{$headerRowData}}" @if($key==$headerkey) selected @endif>{{str_replace("_"," ",ucwords($headerRowData))}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('mappingRow.*') <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="form-btn import-btn text-end">
            <button type="button" wire:click.prevent="importSecond()" class="btn action-btn" wire:loading.attr="disabled" wire.target="importFourth()">Run</button>
        </div>
    </div>
</form>
@elseif(isset($importStep) && !empty($importStep) && $importStep == '3')
<form class="edit-form">
    <div class="card custom-card profile-details import-page">
        <div class="step-buttons">
            <div class="sb-flex">
                <button type="button" class="stp-line active">Upload CSV File</button>
                <button type="button" class="stp-line active">Column Mapping</button>
                <button type="button" class="stp-line active half-line">Import</button>
                <button type="button" class="stp-line">Done</button>
            </div>
        </div>
        <div class="basic-details">
            <h6 class="card-heading">Importing</h6>
            <p class="import-p-text">Your file is now being imported...</p>
        </div>
        <div class="progress-bar-box mb-5" wire:poll.4s="importFourth()">
            <div class="progress">
                <div class="progress-bar bg-primary" role="progressbar" style="width:40%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</form>
@elseif(isset($importStep) && !empty($importStep) && $importStep == '4')
<form class="edit-form">
    <div class="card custom-card profile-details import-page">
        <div class="step-buttons">
            <div class="sb-flex">
                <button type="button" class="stp-line active">Upload CSV File</button>
                <button type="button" class="stp-line active">Column Mapping</button>
                <button type="button" class="stp-line active">Import</button>
                <button type="button" class="stp-line active">Done</button>
            </div>
        </div>
        <div class="basic-details">
            <h6 class="card-heading">Done!</h6>
            <p class="import-p-text">Your import has been completed</p>
        </div>
        <div class="progress-bar-box">
            <div class="progress">
                <div class="progress-bar bg-primary" role="progressbar" style="width:100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="form-btn import-btn">
            <button type="button" wire:click="finishStep()" class="btn action-btn">Finish</button>
        </div>
    </div>
</form>
@endif
@push('foorterscript')
<script>
    $('#fileLoader').change(function() {
        var filepath = this.value;
        var m = filepath.match(/([^\/\\]+)$/);
        var filename = m[1];
        @this.set('selectedFile',filename);
    });
</script>
@endpush