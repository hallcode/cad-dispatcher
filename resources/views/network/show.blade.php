@extends('layouts.sidebar')

@section('title')
<i class="ui {{ $network->color }} certificate icon"></i>{{ $network->name }} / {{ $network->code }}
<br>
<small>
{{ $network->description }}
</small>
@endsection

@section('buttons')
<a class="ui secondary button" href="{{ route('incident.create', ['network' => $network->code]) }}"><i class="fa fa-plus"></i>New Incident</a>
<a class="ui secondary button" href="#"><i class="fa fa-user"></i>Set User Status / Location</a>

@endsection

@section('tabs')
<div class="ui tabular menu" style="border-bottom: none; margin-top: 0;">
    <a href="#" class="active item">
        <i class="fa fa-home"></i> Dash
    </a>
    <a href="#" class="item">
        <i class="fa fa-map-o"></i> Map
    </a>
    <a href="#" class="item">
        <i class="fa fa-users"></i> Members
    </a>
    <a href="#" class="item">
        <i class="fa fa-gear"></i> Settings
    </a>
</div>
@endsection

@section('page-content')
<div id="incident_list">
    <h4>Incidents ({{ $network->incidents->count() }})</h4>
    <p>List of all unresulted incidents in this network.</p>
    <div class="ui message" style="text-align: center" v-if="list.length == 0">
        There are currently no active incidents in this network. <a href="{{ route('incident.create', ['network' => $network->code]) }}">Create a new incident.</a>
    </div>
    <table class="ui small table" v-else>
        <tbody>
            <tr v-for="i in list | orderBy 'due_in_raw'" track-by="id">
                <td>
                    <a href="@{{ i.link }}" class="hover-popup ui" data-content="@{{ i.dets }}" data-variation="basic" data-position="right center">
                        @{{ i.ref }}
                    </a>
                </td>
                <td><div class="ui @{{ i.grade_color }} label">@{{ i.grade_name }}</div></td>
                <td><div class="ui label">@{{ i.type }}</div></td>
                <td>@{{ i.location }}</td>
                <td><i class="fa fa-retweet"></i>@{{ i.updates }}</td>
                <td><i class="fa fa-users"></i>@{{ i.users }}</td>
                <td>
                    <div class="ui red basic label" v-if="i.is_overdue">
                        @{{ i.due_in }}
                    </div>
                    <div class="ui blue basic label" v-else>
                        @{{ i.due_in }}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="ui divider"></div>

<div id="user_list">
    <h4>Users ({{ $network->users->count() }})</h4>
    <p>List of all active users in this network.</p>
    <div class="ui message" style="text-align: center" v-if="list.length == 0">
        There are currently no available users.
    </div>
    <table class="ui table" v-else>
        <tbody>
            <tr v-for="u in list | orderBy 'due_in_raw'" track-by="id">
                <td>
                    <a href="u.link">@{{ u.name }}</a>
                </td>
                <td>@{{ u.call_sign }}</td>
                <td>
                    <div class="ui @{{ u.status_color }} basic label">
                        <i class="ui @{{ u.status_color }} circle icon"></i>
                        @{{ u.status_name }}
                    </div>
                </td>
                <td>@{{ u.location }}</td>
                <td><i class="fa fa-flag"></i>@{{ u.incidents }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="ui divider"></div>
<p style="text-align: center">
    This page will automatically update every 20 seconds.
</p>

<script>
    var iList = new Vue({
        el: '#incident_list',
        data: {
            list: []
        },
        ready: function() {
            this.update();
        },
        methods: {
            update: function() {
                $.get('{{ route('iapi.networkIncidentList', ['code' => $network->code]) }}').done(function(data){
                    iList.list = $.parseJSON(data);
                });
                setTimeout(init_semantic, 500);
                setTimeout(this.update, 20000);
            }
        }
    })
</script>
<script>
    var uList = new Vue({
        el: '#user_list',
        data: {
            list: []
        },
        ready: function() {
            this.update();
        },
        methods: {
            update: function() {
                $.get('{{ route('iapi.networkUserList', ['code' => $network->code]) }}').done(function(data){
                    uList.list = $.parseJSON(data);
                });
                setTimeout(init_semantic, 500);
                setTimeout(this.update, 20000);
            }
        }
    })
</script>
@endsection