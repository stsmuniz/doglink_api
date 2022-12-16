<?php

namespace App\Enums;

enum PageSectionEnum: string
{
    case SocialNetwork = 'SocialNetwork';
    case ExternalLink = 'ExternalLink';
    case UserProfile = 'Profile';
    case EmbedYoutube = 'YoutubePlayer';
    case EmbedSpotify = 'SpotifyPlayer';
    case TextBlock = 'TextBlock';
    case HeaderBlock = 'HeaderBlock';
    case Whatsapp = 'Whatsapp';
}
