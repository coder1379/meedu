<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Announcement.
 *
 * @property int                             $id
 * @property int                             $admin_id
 * @property string                          $announcement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Administrator       $administrator
 * @property mixed                           $destroy_url
 * @property mixed                           $edit_url
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereAnnouncement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Announcement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'admin_id', 'announcement',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'admin_id');
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function getAnnouncementContent()
    {
        return markdown_to_html($this->announcement);
    }

    /**
     * @return Announcement|null
     */
    public static function recentAnnouncement()
    {
        if (config('meedu.system.cache.status')) {
            return Cache::remember('recent_announcement', 360, function () {
                return self::orderByDesc('updated_at')->limit(1)->first();
            });
        }

        return self::orderByDesc('updated_at')->limit(1)->first();
    }
}
