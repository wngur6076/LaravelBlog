<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        /* 태그 */
        // App\Tag::truncate();

        // DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach(array_transpose($tags) as $slug => $names) {
            App\Tag::create([
                'name' => $names['ko'],
                'ko' => $names['ko'],
                'en' => $names['en'],
                'slug' => str_slug($slug)
            ]);
        }

        // $this->command->info('Seeded: tags table');

        // 변수 선언
        $faker = app(Faker\Generator::class);
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        // // 아티클과 태그 연결
        // foreach($articles as $article) {
        //     $article->tags()->sync(
        //         $faker->randomElements(
        //             $tags->pluck('id')->toArray(),
        //             rand(1, 3)
        //         )
        //     );
        // }

        // $this->command->info('Seeded: article_tag table');

        // $articles->each(function ($article) {
        //     $article->comments()->save(factory(App\Comment::class)->make());
        //     $article->comments()->save(factory(App\Comment::class)->make());
        // });

        // $articles->each(function ($article) use ($faker) {
        //     $commentIds = App\Comment::pluck('id')->toArray();

        //     foreach(range(1, 5) as $index) {
        //         $article->comments()->save(
        //             factory(App\Comment::class)->make([
        //                 'parent_id' => $faker->randomElement($commentIds),
        //             ])
        //         );
        //     }
        // });
        // $this->command->info('Seeded: comments table');

        // $comments = \App\Comment::all();

        // $comments->each(function ($comment) {
        //     $comment->votes()->save(factory(App\Vote::class)->make());
        //     $comment->votes()->save(factory(App\Vote::class)->make());
        //     $comment->votes()->save(factory(App\Vote::class)->make());
        // });

        // $this->command->info('Seeded: votes table');

        // foreach(range(1, 10) as $index) {
        //     // 테스트를 위해 고아가 된 첨부파일을 만든다.
        //     // 고아가 된 첨부파일 이란 article_id가 없고 생성된 지 일주일 넘은 테이블 레코드/파일를 의미한다.
        //     $path = $faker->image(attachments_path());
        //     $filename = File::basename($path);
        //     $bytes = File::size($path);
        //     $mime = File::mimeType($path);
        //     $this->command->warn("File saved: {$filename}");

        //     factory(App\Attachment::class)->create([
        //         'filename' => $filename,
        //         'bytes' => $bytes,
        //         'mime' => $mime,
        //         'created_at' => $faker->dateTimeBetween('-1 months'),
        //     ]);
        // }
    }
}