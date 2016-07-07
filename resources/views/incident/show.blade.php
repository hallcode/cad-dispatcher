@extends('layouts.sidebar')

@section('title')
<i class="fa fa-hashtag"></i>
<small>
<a href="{{ route('network.show', ['network' => $network->code]) }}">
    {{ $network->name }}
</a></small> / {{$incident->set_date}} / <b>{{$incident->ref}}</b>
@endsection

@section('buttons')
@if ($incident->trashed() == false)
<a class="ui secondary button" href="{{ route('incident.edit', ['network' => $network->code, 'ref' => $incident->ref, 'date' => $incident->url_date]) }}">
    <i class="fa fa-pencil"></i>
    Edit
</a>
<a class="ui secondary button" href="{{ route('incident.addUpdate', ['network' => $network->code, 'ref' => $incident->ref, 'date' => $incident->url_date]) }}">
    <i class="fa fa-retweet"></i>
    Update
</a>
@else
<a class="ui secondary button" href="#">
    <i class="fa fa-retweet"></i>
    Re-Open
</a>
@endif
@endsection

@section('page-content')
<div class="ui stackable grid">
    <div class="ten wide column">
        <table class="ui basic attribute table">
            <tbody>
                <tr>
                    <td class="four wide">Ref</td>
                    <td class="eleven wide">
                        {{ date('d m y', strtotime($incident->set_timestamp)) }} / {{$incident->ref}}
                    </td>
                </tr>
                <tr>
                    <td>Date / Time</td>
                    <td>
                        <span class="date" style="margin-right: 2em">
                            <i class="fa fa-calendar-o"></i> {{ date('jS F Y', strtotime($incident->set_timestamp)) }}
                        </span>
                        <span class="time">
                            <i class="fa fa-clock-o"></i> {{ date('H:i', strtotime($incident->set_timestamp)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Info</td>
                    <td>
                        <div class="ui {{$incident->grade->color}} label">{{ $incident->grade->name }}</div>
                        <div class="ui label">{{ $incident->type->name }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Details</td>
                    <td>{{$incident->dets}}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>
                        {{$incident->location->formatted_address}}
                        @if (!empty($incident->location->note))
                            {{ $incident->location->note }}
                        @endif
                    </td>
                </tr>
                <tr>
                @if ($incident->trashed())
                    <td>Update Due:</td>
                    <td>Never, this incident is closed.</td>
                @else
                    @if ($incident->is_overdue == false)
                        <td>Next Update Due <small>dd:hh:mm</small></td>
                    @else
                        <td>Update Overdue By <small>dd:hh:mm</small></td>
                    @endif
                    <td>{{$incident->due_in}}</td> 
                @endif
                </tr>
                <tr>
                    <td>Users Assigned</td>
                    @if ($incident->users->count() == 0)
                    <td>(None)</td>
                    @else
                    <td>
                    @foreach ($incident->users as $user)
                        {!! $user->label !!}
                    @endforeach
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <h4>Updates ({{$incident->updates->count()}})</h4>
        
        @if ($incident->updates->count() == 0)
        <p>There are no updates to show.</p>
        @else
        @foreach ($incident->updates as $update)
        <div class="ui fluid card">
            <div class="content">
                <div class="meta">
                    <span class="date">
                        <i class="fa fa-calendar-o"></i> {{ date('d M Y', strtotime($update->created_at)) }}
                    </span>
                    <span class="time">
                        <i class="fa fa-clock-o"></i> {{ date('H:i', strtotime($update->created_at)) }}
                    </span>
                    <span class="users">
                        <i class="fa fa-users"></i>
                        @foreach ($update->users as $user)
                        <span class="item">{{ $user->first_name }} {{ $user->last_name }} {{ $user->serial }}</span>
                        @endforeach
                    </span>
                    @if ($update->result)
                    <span class="right floated isResult"><i class="fa fa-check"></i> Result</span>
                    @endif
                </div>
                <p style="margin-top: 0.5rem">
                    {{$update->dets}}
                </p>
                <div class="description"><i class="fa fa-map-marker"></i>{{ $update->location->formatted_address }}</div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
    <div class="five wide column">
    <div style="height: 300px">
    {!! $map->render() !!}
    </div>
    <!--
        <h4>Uploads (0)</h4>
        <div class="ui four doubling cards">
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
        </div>
        <div class="ui fluid card">
            <div class="content">
                <div class="right floated meta">JPEG / 52kb</div>
                <i class="fa fa-file-image-o"></i> <a href="#">Document Title</a>
                <div class="description">Description of the content etc.</div>
            </div>
            <div class="content">
                <div class="right floated meta">PDF / 52kb</div>
                <i class="fa fa-file-pdf-o"></i> <a href="#">Document Title</a>
                <div class="description">Description of the content etc.</div>
            </div>
        </div>
        -->
    </div>
</div>
<div class="ui divider" style="margin-top: 2rem"></div>
<p style="text-align: center">
This page does not automatically update.
</p>
@endsection