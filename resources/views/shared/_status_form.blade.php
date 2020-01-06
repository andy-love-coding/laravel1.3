<form action="{{ route('statuses.store') }}" method="post">
  @include('shared._errors')
  @csrf
  <textarea class="form-control" name="content" rows="3" placeholder="聊聊新鲜事儿..." required>{{ old('content') }}</textarea>
  <div class="text-right">
    <button type="submit" class="btn btn-primary mt-3">发布</button>
  </div>
</form>