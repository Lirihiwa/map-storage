<x-app-layout>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<form action="{{ route('beatmaps.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="file" name="osz_file" id="beatmap_file" accept=".osz" required>
				<button type="submit">Загрузить</button>
			</form>

			@if ($errors->any())
				<div style="color: red;">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
	</div>
</x-app-layout>