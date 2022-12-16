<?php

namespace App\Enums;

enum SocialNetworkEnum: string
{
    case Twitter = 'Twitter';
    case Instagram = 'Instagram';
    case LinkedIn = 'LinkedIn';
    case Facebook = 'Facebook';
    case DevTo = 'Dev';
    case TikTok = 'TikTok';
    case Github = 'Github';
    case YouTube = 'YouTube';
    case Spotify = 'Spotify';
    case Steam = 'Steam';

    public function url(): string
    {
        return match ($this) {
            SocialNetworkEnum::Twitter      => 'https://twitter.com',
            SocialNetworkEnum::Instagram    => 'https://instagram.com',
            SocialNetworkEnum::LinkedIn     => 'https://www.linkedin.com/in/',
            SocialNetworkEnum::Facebook     => 'https://www.facebook.com/',
            SocialNetworkEnum::DevTo        => 'https://dev.to/',
            SocialNetworkEnum::TikTok       => 'https://www.tiktok.com/',
            SocialNetworkEnum::Github       => 'https://github.com/',
            SocialNetworkEnum::YouTube      => 'https://www.youtube.com/channel/',
            SocialNetworkEnum::Spotify      => 'https://open.spotify.com/user/',
            SocialNetworkEnum::Steam        => 'https://steamcommunity.com/id/',
        };
    }
}
