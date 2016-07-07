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
@if (Auth::user()->can('mod', $network))
<a class="ui secondary button" href="{{ route('n.updateUsers', ['network' => $network->code]) }}"><i class="fa fa-user"></i>Set User Status / Location</a>
@endif
<a class="ui secondary button" href="{{ route('n.leave', ['network' => $network->code]) }}"><i class="fa fa-sign-out"></i>Leave Network</a>
@endsection

@section('tabs')
<div class="ui tabular menu" style="border-bottom: none; margin-top: 0;">
    <a href="#" class="active item">
        <i class="fa fa-home"></i> Dash
    </a>
    <!-- not yet implemented
    <a href="#" class="item">
        <i class="fa fa-map-o"></i> Map
    </a>
    <a href="#" class="item">
        <i class="fa fa-users"></i> Members
    </a>
    @if (Auth::user()->can('mod', $network))
    <a href="#" class="item">
        <i class="fa fa-gear"></i> Settings
    </a>
    @endif
    -->
</div>
@endsection

@section('page-content')
<div id="incident_list">
    <h4>Incidents (@{{ list.length }})</h4>
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
    <h4>Users (@{{ list.length }})</h4>
    <p>List of all active users in this network.</p>
    <div class="ui message" style="text-align: center" v-if="list.length == 0">
        There are currently no available users.
    </div>
    <table class="ui small table" v-else>
        <tbody>
            <tr v-for="u in list | orderBy 'order' -1" track-by="id">
                <td>
                    <a href="@{{u.link}}">@{{ u.name }}</a>
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