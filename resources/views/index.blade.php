<div class="btn-group" id="toolbar">
    <a class="btn btn-primary btn-sm expandAll"><i class="fa fa-plus-square-o"></i>展开</a>
    <a class="btn btn-primary btn-sm collapseAll"><i class="fa fa-minus-square-o"></i>收起</a>
</div>
<div class="pull-right">
    <div class="btn-group pull-right" style="margin-right: 10px">
        <a href="{{$urls['create']}}" class="btn btn-sm btn-success" title="新增">
            <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
        </a>
    </div>

</div>
<table id="treeTable" {!! $attributes !!} style="width:100%">
    <thead>
    <tr>
        @foreach($headers as $header)
            <th>{{ $header['title'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr data-tt-id='{{$row['id']}}'@if($row['parent_id'] > 0) data-tt-parent-id='{{$row['parent_id']}}'@endif>
        @foreach($columns as $key=>$val)
            @if($key === 0)
                <td><span class='@if(count($row->allChildrenDicts) > 0) folder @else file @endif'>{{ $row[$val] }}</span></td>
            @elseif($val == 'operate')
                <td>
                    @foreach($operates as $ops)
                        <a href="{{ @sprintf($ops['url'], $row['id']) }}" data-id="{{$row['id']}}" class="{{$ops['cls']}}" haschild="{{count($row->allChildrenDicts)}}"><i class="fa fa-{{$ops['cls']}}"></i></a>&nbsp;
                    @endforeach
                </td>
            @else
                <td>
                    @if(isset($formats[$val]))
                        {!! $formats[$val][$row[$val]] !!}
                    @else
                        {{ $row[$val] }}
                    @endif
                </td>
            @endif
        @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(function(){
        $("#treeTable").treetable({ expandable: true });
        $('#toolbar .expandAll').click(function(){
            $('#treeTable').treetable('expandAll');
        });
        $('#toolbar .collapseAll').click(function(){
            $('#treeTable').treetable('collapseAll');
        });
        $('.trash').click(function(){
            var url = $(this).attr('href');
            $(this).attr('href','javascript:void(0);');
            var hasChild = $(this).attr('haschild');
            var title = hasChild > 0 ? '删除将同时删除子级，确认删除？' : '确认删除？';
            swal({
                title: title,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: url,
                            data: {
                                _method:'delete',
                                _token:LA.token,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');
                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(result) {
                var data = result.value;
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            });
        });
    });
</script>