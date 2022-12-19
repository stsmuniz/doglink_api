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
    case Pinterest = 'Pinterest';
    case Dribbble = 'Dribbble';
    case Soundcloud = 'Soundcloud';

    public function url(): array
    {
        return match ($this) {
            SocialNetworkEnum::Twitter      => ['https://twitter.com'],
            SocialNetworkEnum::Instagram    => ['https://instagram.com'],
            SocialNetworkEnum::LinkedIn     => ['https://www.linkedin.com/in/', 'https://linkedin.com/in/', 'https://www.linkedin.com/company/', 'https://linkedin.com/company/'],
            SocialNetworkEnum::Facebook     => ['https://www.facebook.com/'],
            SocialNetworkEnum::DevTo        => ['https://dev.to/'],
            SocialNetworkEnum::TikTok       => ['https://www.tiktok.com/', 'https://tiktok.com/@'],
            SocialNetworkEnum::Github       => ['https://github.com/'],
            SocialNetworkEnum::YouTube      => ['https://www.youtube.com/channel/', 'https://www.youtube.com/@', 'https://youtube.com/@'],
            SocialNetworkEnum::Spotify      => ['https://open.spotify.com/user/'],
            SocialNetworkEnum::Steam        => ['https://steamcommunity.com/id/'],
            SocialNetworkEnum::Pinterest    => ['https://pinterest.com/'],
            SocialNetworkEnum::Dribbble     => ['https://dribbble.com/'],
            SocialNetworkEnum::Soundcloud   => ['https://soundcloud.com/'],
        };
    }
}
