# MCP Configuration for Hook Project

This directory contains MCP (Model Context Protocol) server configurations for enhanced AI-assisted development.

## Quick Setup

### 1. Install VS Code MCP Extension
Install the official **MCP** extension by Model Context Protocol:
- Open VS Code
- Press `Ctrl+P` and search: `ext install modelcontextprotocol.mcp`

### 2. Configure MCP Servers
Copy `.mcp.json` to your VS Code MCP settings:
- **Mac/Linux**: `~/Library/Application Support/Code/User/globalStorage/mcp.json`
- **Windows**: `%APPDATA%\Code\User\globalStorage\mcp.json`

Or add directly to your VS Code `settings.json`:
```json
"mcp.servers": {
  "context7": {
    "command": "npx",
    "args": ["-y", "context7-mcp"]
  }
}
```

### 3. Restart VS Code

## MCP Servers Configured

### Context7 (Recommended)
**Purpose**: Up-to-date code documentation for LLMs
- Laravel, Filament, React, Vue, Inertia, Tailwind docs
- Always current - no outdated responses

**Setup**:
```bash
npx -y context7-mcp
```

### MySQL MCP
**Purpose**: Database schema exploration and read-only queries
- Browse database structure
- Execute safe read-only SQL queries

**Setup** (requires MySQL running):
```bash
npm install -g @modelcontextprotocol/server-mysql
```

**Environment Variables**:
- `MYSQL_HOST` - Database host (default: localhost)
- `MYSQL_PORT` - Database port (default: 3306)
- `MYSQL_USER` - Database user
- `MYSQL_PASSWORD` - Database password
- `MYSQL_DATABASE` - Database name

### Docker MCP
**Purpose**: Container and image management
- Manage Docker containers
- View logs, stats, and manage deployments

**Setup**:
```bash
docker run --rm -i --network=host cloudflare/cloudflare-warp:latest
```

## Alternative: Claude Desktop

If using Claude Desktop, add to `~/Library/Application Support/Claude/claude_desktop_config.json`:

```json
{
  "mcpServers": {
    "context7": {
      "command": "npx",
      "args": ["-y", "context7-mcp"]
    },
    "mysql": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-mysql"],
      "env": {
        "MYSQL_HOST": "localhost",
        "MYSQL_PORT": "3306",
        "MYSQL_USER": "root",
        "MYSQL_PASSWORD": "",
        "MYSQL_DATABASE": "hook"
      }
    }
  }
}
```

## Testing MCP Connection

After setup, test with:
```
MCP server working? (server: context7)
```
