@extends('admin::layouts.master')

@section('title', 'test')


<style>
    body, html {
  background-color: #2f3031;
}
.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
  background-color: #ffffff;
}

.help_icon {
  position: fixed;
  bottom:0;
  right: 0;
  padding: 10px;
}

input[type="checkbox"] {
  width: 1.2rem;
  height: 1.2rem;
  border-radius: 50%;
}
</style>