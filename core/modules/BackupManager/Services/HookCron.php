<?php

namespace SoosyzeCore\BackupManager\Services;

class HookCron
{
    private $backupservice;
    
    private $config;
    
    public function __construct(BackupService $bs, $config)
    {
        $this->backupservice = $bs;
        $this->config = $config;
    }
    
    public function hookAppCron()
    {
        if ($this->config->get('settings.backup_cron')) {
            $this->backupservice->doBackup();
        }
    }
}
