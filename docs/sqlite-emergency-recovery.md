# SQLite 紧急恢复与失败回退

本文适用于当前默认数据库连接为文件型 SQLite 的部署。恢复会替换该连接配置指向的数据库文件；请只在确认业务需要回退时执行。

## 恢复前准备

1. 记录故障时间、症状和准备恢复的备份文件路径。
2. 暂停会写入数据库的 Web 请求、队列 Worker 和计划任务。必要时先执行 `php artisan down`。
3. 确认候选备份是由 `thlin:db-backup` 创建，且同目录存在同名 `.json` manifest。
4. 不要手工复制、重命名或覆盖 `database.sqlite`；使用下述命令保留预检、备份和校验保护。

备份默认位于 `storage/app/backups/sqlite/`，该目录已被 Git 忽略。

## 先执行预检

将路径替换为候选 `.sqlite` 备份的绝对路径：

```bash
php artisan thlin:db-restore /absolute/path/to/sqlite-backup-YYYYMMDD_HHMMSS_microseconds.sqlite --dry-run
```

预检会确认以下内容，且不会修改当前数据库：

- 备份文件与同名 manifest 存在并可读；
- 文件具有 SQLite 格式，且 SHA-256、文件大小与 manifest 一致；
- manifest 中记录了 migration 状态；
- 未提供 `--force` 时，命令只会预检，不会覆盖数据库。

只有看到 `Restore preflight passed.` 并确认 migration 状态符合预期，才继续下一节。

## 正式恢复

```bash
php artisan thlin:db-restore /absolute/path/to/sqlite-backup-YYYYMMDD_HHMMSS_microseconds.sqlite --force
```

`--force` 是覆盖当前数据库的显式确认。命令会按固定顺序执行：

1. 再次运行预检。
2. 使用 `thlin:db-backup` 为当前数据库创建新的安全备份及 manifest。
3. 将候选备份复制到受控临时文件，执行 `PRAGMA integrity_check`、`PRAGMA foreign_key_check`，并核对其 migration 状态与 manifest。
4. 仅在所有检查通过后，替换当前 SQLite 数据库文件。

成功时会显示 `SQLite database restored.`。随后重新启动已暂停的服务；如使用维护模式，执行：

```bash
php artisan up
```

## 恢复后验证

1. 运行关键页面和 CMS 登录检查，确认可读取预期内容。
2. 检查关键表的记录数、父子页面关系及管理员访问是否与备份基线一致。
3. 运行 migration 状态检查，确认没有意外的待执行或未知 migration：

```bash
php artisan migrate:status
```

4. 在维护记录中保留使用的候选备份路径、自动生成的恢复前备份路径、执行时间和验证结果。

## 失败与回退

恢复会在下列任一情况停止，不会覆盖当前数据库：缺失或无效 manifest、SHA-256/大小不匹配、非 SQLite 文件、数据库完整性错误、外键违规、migration 状态不匹配，或恢复前备份失败。

如果正式恢复后发现结果不符合预期：

1. 保持写入暂停，不要手工修改或覆盖数据库文件。
2. 找到本次恢复命令自动生成的最新恢复前备份及其 manifest（位于 `storage/app/backups/sqlite/`）。
3. 对该恢复前备份先运行 `--dry-run`；预检通过后，再以 `--force` 恢复它。
4. 重复“恢复后验证”步骤，记录回退原因和结果。

若预检或恢复命令本身失败，保留命令输出和候选备份，不要跳过检查强制复制文件；先排查 manifest、磁盘权限、SQLite 文件完整性及当前数据库路径配置。
